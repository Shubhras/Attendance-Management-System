import BottomSheet, {
  BottomSheetBackdrop,
  BottomSheetView,
} from '@gorhom/bottom-sheet';
import React, { useCallback, useState } from 'react';
import {
  View,
  TouchableOpacity,
  Image,
  Alert,
  PermissionsAndroid,
  Platform,
  ActivityIndicator,
} from 'react-native';
import { scale } from 'react-native-size-matters';
import FastImage from '@d11/react-native-fast-image';
import styles from './styles';
import { Colors, LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import Button from '../../buttons/Button';
import TextInput from '../../inputs/TextInput';
import DateTimePickerModal from 'react-native-modal-datetime-picker';
import { AllEmpReports } from '../../../api/auth';
import DownloadReport from '../../../assets/images/Animation/Download.gif';
import { API_URL } from '../../../../env';
import RNFetchBlob from 'rn-fetch-blob';

const MyExportBottomSheet = ({ sheetRef, onCancel, token }) => {
  const [startDate, setStartDate] = useState(null);
  const [endDate, setEndDate] = useState(null);
  const [isDatePickerVisible, setDatePickerVisibility] = useState(false);
  const [currentPicker, setCurrentPicker] = useState(null);
  const [done, setDone] = useState(false);
  const [loading, setLoading] = useState(false);

  console.log('date', startDate, endDate);

  const allEmployeeReports = async (startYMD, endYMD) => {
    setLoading(true);
    handeleDownloadReport(
      `${API_URL}/api/operator/attendance/report/pdf?start_date=${startYMD}&end_date=${endYMD}`,
    );

    return;

    AllEmpReports(token, startYMD, endYMD)
      .then(val => {
        console.log('val', val);
        setLoading(false);
      })
      .catch(err => {
        console.log('error', err?.message);
        setLoading(false);
      });
  };

  // -----------------------------------------------
  //  PERMISSION
  // -----------------------------------------------
  const requestStoragePermission = async () => {
    try {
      let granted = false;
      const androidVersion = parseInt(Platform.Version, 10);

      if (androidVersion >= 33) {
        const permissions = await PermissionsAndroid.requestMultiple([
          PermissionsAndroid.PERMISSIONS.READ_MEDIA_IMAGES,
          PermissionsAndroid.PERMISSIONS.READ_MEDIA_VIDEO,
          PermissionsAndroid.PERMISSIONS.READ_MEDIA_AUDIO,
        ]);

        granted = Object.values(permissions).some(
          p => p === PermissionsAndroid.RESULTS.GRANTED,
        );
      } else {
        const res = await PermissionsAndroid.request(
          PermissionsAndroid.PERMISSIONS.WRITE_EXTERNAL_STORAGE,
        );
        granted = res === PermissionsAndroid.RESULTS.GRANTED;
      }

      return granted;
    } catch (error) {
      console.log(error);
      setLoading(false);
      return false;
    }
  };

  // -----------------------------------------------
  //  DOWNLOAD FUNCTION (Correct + Token supported)
  // -----------------------------------------------
  const downloadFile = async (url, token) => {
    const fs = RNFetchBlob.fs;

    const downloadDir = Platform.select({
      android: fs.dirs.DownloadDir,
      ios: fs.dirs.DocumentDir,
    });

    const filename = `contractor_report_${Date.now()}.pdf`;
    const filePath = `${downloadDir}/${filename}`;

    try {
      const configOptions = Platform.select({
        ios: {
          fileCache: true,
          path: filePath,
          appendExt: 'pdf',
        },
        android: {
          fileCache: true,
          path: filePath,
          appendExt: 'pdf',
          addAndroidDownloads: {
            useDownloadManager: true,
            notification: true,
            path: filePath,
            description: 'Downloading Contractor Report...',
          },
        },
      });

      const res = await RNFetchBlob.config(configOptions).fetch('GET', url, {
        Authorization: `Bearer ${token}`,
        Accept: 'application/pdf',
      });

      return res;
    } catch (error) {
      console.log('Download Error:', error);
      Alert.alert('Download Failed', error.message);
      setLoading(false)
      return null;
    }
  };

  //  DOWNLOAD HANDLER
  // -----------------------------------------------
  const handeleDownloadReport = async PDF_URL => {
    if (!PDF_URL) {
      Alert.alert('Error', 'Invalid download URL');
      setLoading(false);
      return;
    }

    if (Platform.OS === 'android') {
      const ok = await requestStoragePermission();
      if (!ok) return;

      const res = await downloadFile(PDF_URL, token);
      if (res) {
        setDone(true);
        setLoading(false);
      }
      setTimeout(() => {
        onCancel();
        setStartDate('');
        setEndDate('');
        setDone(false);
      }, 3000);
      //  Alert.alert("Download Complete", "Saved in Downloads.");
    } else {
      const res = await downloadFile(PDF_URL, token);
      if (res) {
        Alert.alert('Download Complete', `Saved in Documents`, [
          { text: 'OK' },
          {
            text: 'Open PDF',
            onPress: () => RNFetchBlob.ios.previewDocument(res.path()),
          },
        ]);
      }
    }
  };

  const showDatePicker = picker => {
    setCurrentPicker(picker);
    setDatePickerVisibility(true);
  };

  const hideDatePicker = () => {
    setDatePickerVisibility(false);
    setCurrentPicker(null);
  };

  // const handleConfirm = date => {
  //   if (currentPicker === 'start') {
  //     setStartDate(date);
  //   } else if (currentPicker === 'end') {
  //     setEndDate(date);
  //   }
  //   hideDatePicker();
  // };

  const handleConfirm = date => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const selected = new Date(date);
    selected.setHours(0, 0, 0, 0);

    // ---------- START DATE RULES ----------
    if (currentPicker === 'start') {
      // 1. Start date cannot be in future
      if (selected > today) {
        Alert.alert('Invalid Date', 'Start date cannot be in the future');
        hideDatePicker();
        return;
      }

      setStartDate(selected);

      // Reset end date after new start
      setEndDate(null);

      hideDatePicker();
      return;
    }

    // ---------- END DATE RULES ----------
    if (currentPicker === 'end') {
      if (!startDate) {
        Alert.alert('Missing Start Date', 'Please select start date first');
        hideDatePicker();
        return;
      }

      // 1. End date cannot be before start date
      if (selected < startDate) {
        Alert.alert('Invalid Date', 'End date cannot be before start date');
        hideDatePicker();
        return;
      }

      // 2. End date cannot be in future
      if (selected > today) {
        Alert.alert('Invalid Date', 'End date cannot be in the future');
        hideDatePicker();
        return;
      }

      // 3. End month must be between startMonth → currentMonth
      const startMonth = startDate.getMonth();
      const endMonth = today.getMonth();

      if (selected.getMonth() < startMonth || selected.getMonth() > endMonth) {
        Alert.alert(
          'Invalid Month',
          'End date must be between start month and current month',
        );
        hideDatePicker();
        return;
      }

      setEndDate(selected);
      hideDatePicker();
    }
  };

  const renderBackdrop = useCallback(
    props => (
      <BottomSheetBackdrop
        {...props}
        opacity={0.5}
        appearsOnIndex={0}
        disappearsOnIndex={-1}
      />
    ),
    [],
  );

  const handleSubmit = () => {
    if (!startDate) {
      Alert.alert('Missing Start Date', 'Please select start date first');
      return;
    }

    if (!endDate) {
      Alert.alert('Missing End Date', 'Please select end date');
      return;
    }

    const startYMD = formatToYMD(startDate);
    const endYMD = formatToYMD(endDate);

    allEmployeeReports(startYMD, endYMD);

    // console.log("Start Date 👉", startYMD);
    // console.log("End Date 👉", endYMD);
  };

  const formatToYMD = date => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  return (
    <BottomSheet
      ref={sheetRef}
      index={-1}
      enablePanDownToClose={false}
      enableHandlePanningGesture={false}
      enableContentPanningGesture={false}
      snapPoints={['50%']}
      backdropComponent={renderBackdrop}
      backgroundStyle={{
        backgroundColor: '#fff',
        borderTopLeftRadius: scale(10),
        borderTopRightRadius: scale(10),
        padding: scale(16),
      }}
      handleIndicatorStyle={styles.indicator}
    >
      <BottomSheetView style={styles.mainwrapper}>
        {done ? (
          <View style={styles.card}>
            <View style={styles.imageContainer}>
              <FastImage
                source={require('../../../assets/images/Animation/Download.gif')}
                defaultSource={DownloadReport}
                style={styles.image}
                resizeMode="contain"
              />
            </View>
            <CustomText
              style={[
                styles.titleSuccess,
                { color: LightThemeColors.titleColor },
              ]}
            >
              Full Employee Report Download Successfully.
            </CustomText>
          </View>
        ) : (
          <>
            <CustomText
              style={[styles.titlePIN, { color: LightThemeColors.titleColor }]}
            >
              Select Export Date
            </CustomText>

            <TouchableOpacity
              onPress={() => showDatePicker('start')}
              style={styles.textInputWrapper}
            >
              <TextInput
                label="Start Date"
                placeholderTextColor={Colors.greyDark}
                backgroundColor={Colors.inputBackgroundColor}
                textInputValueColor={Colors.black}
                placeholder="Enter start date"
                value={
                  startDate
                    ? startDate.toLocaleDateString('en-GB', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                      })
                    : ''
                }
                editable={false}
              />
            </TouchableOpacity>

            <TouchableOpacity
              onPress={() => showDatePicker('end')}
              style={styles.textInputWrapper}
            >
              <TextInput
                label="End Date"
                placeholderTextColor={Colors.greyDark}
                backgroundColor={Colors.inputBackgroundColor}
                textInputValueColor={Colors.black}
                placeholder="Enter end date"
                value={
                  endDate
                    ? endDate.toLocaleDateString('en-GB', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                      })
                    : ''
                }
                editable={false}
              />
            </TouchableOpacity>

            <View style={styles.buttonView}>
              {loading ? (
                <ActivityIndicator
                  size="large"
                  color={LightThemeColors.titleColor}
                  style={{ marginRight: scale(8) }}
                />
              ) : (
                <Button
                  label="Submit"
                  labelColor={Colors.white}
                  backgroundColor={LightThemeColors.titleColor}
                  style={styles.button}
                  onPress={handleSubmit}
                />
              )}
            </View>
          </>
        )}

        <DateTimePickerModal
          isVisible={isDatePickerVisible}
          mode="date"
          display="spinner"
          onConfirm={handleConfirm}
          onCancel={hideDatePicker}
        />
      </BottomSheetView>
    </BottomSheet>
  );
};

export default MyExportBottomSheet;
