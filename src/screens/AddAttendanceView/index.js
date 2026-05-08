import React, { useState } from 'react';
import {
  View,
  ActivityIndicator,
  StatusBar,
  Image,
  Pressable,
  Text,
} from 'react-native';
import FastImage from '@d11/react-native-fast-image';
import styles from './styles';
import { Colors, LightThemeColors } from '../../config/Colors';
import { Images } from '../../constants/images';
import { CustomText } from '../../components/global/CustomComponents';
import Button from '../../components/buttons/Button';
import { SafeAreaView } from 'react-native-safe-area-context';
import Morfin from '../../../MorfinAuth';
import { getEmployees } from '../../../db';
import { AttendanceMark } from '../../api/auth';
import { useSelector } from 'react-redux';
import { FONT_SIZE_LG, SCREEN_WIDTH } from '../../config/Constants';
import Icons from '../../components/Icons/Icons';
import { scale } from 'react-native-size-matters';

const AddAttendanceView = ({ navigation, route }) => {
  const myCardData = route?.params?.myCardData || {};
  const machineItem = route?.params?.machineItem || {};

  console.log('myCardDatamyCardData', myCardData);
  console.log('machineItemmachineItem', machineItem);

  const [attendance, setAttendance] = useState(false);
  const [loading, setLoading] = useState(false);
  const [status, setStatus] = useState('');
  const [wrongFinger, setWrongFinger] = useState(false);
  const [fingerImage, setFingerImage] = useState('');
  const [matchedUser, setMatchedUser] = useState(null);
  const user = useSelector(state => state?.users);
  const access_token = user?.users?.access_token;
  // 🚀 MAIN FUNCTION
  const onCaptureFinger = async () => {
    // FingerPrintEmployee(127);
    // setWrongFinger(true);
    // return;
    // const employees = await getEmployees("MFS500");
    // setTimeout(() => {

    //   console.log("employeesemployeesemployeesemployeesemployees",employees);
    // }, 9000);
    // return

    try {
      setLoading(true);
      setStatus('Checking device...');
      setWrongFinger(false);
      setAttendance(false);
      setFingerImage('');
      // 1. Device check
      const connected = await Morfin.isDeviceConnected();

      if (!connected) {
        setStatus('❌ Device not connected');
        setLoading(false);
        return;
      }

      // 2. Init
      setStatus('Initializing device...');
      await Morfin.initDevice();

      // 3. Capture
      setStatus('Place finger on scanner...');
      await Morfin.autoCapture(60, 10000);

      // 4. Get template + image
      const capturedTemplate = await Morfin.getTemplate();
      const image = await Morfin.getImage();

      setFingerImage(image);

      if (!capturedTemplate || capturedTemplate.length < 50) {
        setStatus('Invalid fingerprint ❌');
        setLoading(false);
        return;
      }

      // 5. Load employees
      setStatus('Loading employees...');
      const employees = await getEmployees('MFS500');

      if (!employees.length) {
        setStatus('No employees founds', employees?.length);
        setLoading(false);
        return;
      }

      // 6. Filter valid templates
      const validEmployees = employees.filter(
        e => e.fingerprintdata && e.fingerprintdata.length > 50,
      );

      if (!validEmployees.length) {
        setStatus('No valid fingerprint data');
        setLoading(false);
        return;
      }

      // 7. Extract templates
      const templates = validEmployees.map(e => e.fingerprintdata);

      // 8. Match
      setStatus('Matching fingerprint...');
      const res = await Morfin.matchTemplatesFast(capturedTemplate, templates);

      // 9. Result
      if (res?.matched && res.index !== -1) {
        const emp = validEmployees[res.index];

        setMatchedUser(emp);
        setAttendance(true);
        setStatus('');

        // 👉 API call here
        // markAttendance(emp)
        FingerPrintEmployee(emp?.id);
      } else {
        setWrongFinger(true);
        setMatchedUser(null);
        setStatus('Fingerprint not matched ❌');
      }
    } catch (error) {
      console.log(error);
      setStatus('Error: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  const FingerPrintEmployee = async empID => {
    const data = {
      date: Date().toString(),
      employee_id: empID,
      status: 1,
      scan_status: 1,
      clock_in: '',
      clock_out: '',
      machine_id: machineItem?.id ? machineItem?.id : null,
    };
    console.log('data', data);
    AttendanceMark(access_token, data)
      .then(response => {
        console.log('GetEmployeesWithoutFingerprint', response);
        setLoading(false);
        setAttendance(true);
      })
      .catch(error => {
        console.log('error', error);
        setStatus(
          `Server error: ${
            error?.message?.toString() ?? 'Try after some time.'
          }`,
        );
        setLoading(false);
        setWrongFinger(true);
        setAttendance(false);
      });
  };

  const onCancel = () => {
    setAttendance(false);
    setStatus('');
    setFingerImage('');
    setWrongFinger(false);
    setMatchedUser(null);
  };

  return (
    <SafeAreaView style={[{ backgroundColor: Colors.white, flex: 1 }]}>
      <StatusBar
        animated={true} // Animate transitions between style changes
        backgroundColor={Colors.white} // Make status bar transparent (requires translucent=true)
        barStyle="dark-content" // Set text and icon color (light-content or dark-content)
        hidden={false} // Show or hide the status bar
        translucent={true} // Allow content to draw under the status bar
      />
      <Pressable
        style={{ padding: scale(20), flexDirection: 'row' }}
        onPress={() => {
          navigation.goBack();
        }}
      >
        <Icons
          iconType={'Ionicons'}
          name="chevron-back"
          size={scale(22)}
          color={Colors.black}
        />
        <View>
          <Text
            style={[
              styles.fingerTitle,
              { color: Colors.black, marginLeft: scale(10) },
            ]}
          >
            {myCardData?.title || 'Add Attendance'}
          </Text>
        </View>
      </Pressable>
      {machineItem?.name && (
        <Text
          style={[
            styles.fingerTitle,
            { color: Colors.black, marginLeft: scale(10) },
          ]}
        >
          Machine Name:{' '}
          {machineItem?.name
            ? machineItem?.name
            : myCardData?.title || 'Add Attendance'}
        </Text>
      )}
      <View style={styles.mainwrapper}>
        {/* 👤 MATCHED USER */}
        {matchedUser && (
          <View style={styles.card}>
            <View style={styles.imageContainer}>
              {matchedUser?.profileimg != null ? (
                <FastImage
                  source={{
                    uri: matchedUser?.profileimg,
                    priority: FastImage.priority.high,
                  }}
                  style={styles.image}
                />
              ) : (
                <FastImage source={Images.profileImages} style={styles.image} />
              )}
            </View>

            <CustomText
              style={[
                styles.title,
                { color: LightThemeColors.titleColor, fontSize: FONT_SIZE_LG },
              ]}
            >
              {matchedUser?.name}
            </CustomText>

            <CustomText style={styles.subTitle}>
              {matchedUser?.empId}
            </CustomText>
          </View>
        )}

        {/* ✅ SUCCESS */}
        {attendance ? (
          <>
            <View style={styles.imageContainer}>
              <FastImage
                source={Images.FingerprintSuccess}
                style={styles.image}
              />
            </View>

            <CustomText
              style={[
                styles.titleSuccess,
                { color: LightThemeColors.titleColor },
              ]}
            >
              Attendance marked successfully
            </CustomText>

            <Button
              label="Scan Again"
              onPress={onCancel}
              style={styles.button}
              backgroundColor={LightThemeColors.titleColor}
              labelColor={Colors.white}
            />
          </>
        ) : (
          <>
            {/* 👇 Fingerprint Image */}
            <View style={styles.imageContainer}>
              {/* <FastImage
                source={
                  fingerImage
                    ? { uri: `data:image/png;base64,${fingerImage}` }
                    : wrongFinger
                    ? Images.WrongFingerPrint
                    : Images.FingerPrintScan
                }
                style={styles.image}
              /> */}
              {/* {fingerImage && !wrongFinger && (
                <FastImage
                  source={
                    fingerImage
                      ? { uri: `data:image/png;base64,${fingerImage}` }
                      : wrongFinger
                      ? Images.WrongFingerPrint
                      : Images.FingerPrintScan
                  }
                  style={styles.image}
                />
              )}
              {wrongFinger && (
                <FastImage
                  source={Images.WrongFingerPrint}
                  style={styles.image}
                />
              )}
              {!wrongFinger && fingerImage == '' && (
                <FastImage
                  source={Images.FingerPrintScan}
                  style={styles.image}
                />
              )} */}
              <FastImage
                key={fingerImage || wrongFinger} // 👈 force re-render
                source={
                  wrongFinger
                    ? Images.WrongFingerPrint
                    : fingerImage
                    ? { uri: `data:image/png;base64,${fingerImage}` }
                    : Images.FingerPrintScan
                }
                style={styles.image}
              />
            </View>

            <CustomText
              style={[styles.title, { color: LightThemeColors.titleColor }]}
            >
              Place your finger on the scanner
            </CustomText>

            {/* STATUS */}
            {status ? (
              <CustomText
                style={{
                  width: SCREEN_WIDTH * 0.9,
                  color: Colors.error,
                  textAlign: 'center',
                }}
              >
                {status}
              </CustomText>
            ) : null}

            {/* BUTTON / LOADER */}
            {loading ? (
              <ActivityIndicator
                size="large"
                color={LightThemeColors.titleColor}
              />
            ) : (
              <Button
                label="Capture Finger"
                onPress={onCaptureFinger}
                style={styles.button}
                backgroundColor={LightThemeColors.titleColor}
                labelColor={Colors.white}
              />
            )}
          </>
        )}
      </View>
    </SafeAreaView>
  );
};

export default AddAttendanceView;
