import BottomSheet, {
  BottomSheetBackdrop,
  BottomSheetView,
} from '@gorhom/bottom-sheet';
import React, { useCallback, useEffect, useState } from 'react';
import { View, ActivityIndicator, Image } from 'react-native';
import FastImage from '@d11/react-native-fast-image';
import FingerPrintSuccess from '../../../assets/images/Animation/FingerprintSuccess.gif';
import FingerPrintScan from '../../../assets/images/Animation/fingerprintScan.gif';
import WrongFingerprint from '../../../assets/images/Animation/WrongFingerprint.gif';
import { scale } from 'react-native-size-matters';
import styles from './styles';
import { Colors, LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import Button from '../../buttons/Button';
import Morfin from '../../../../MorfinAuth';
import { AddFingerPrint } from '../../../api/auth';
import { Images } from '../../../constants/images';
import { createTable, syncEmployees } from '../../../../db';

const AddFingerPrintBottomSheet = ({
  sheetRef,
  onCancel,
  token,
  userId,
  HandType,
  FingerType,
  onUpdatedFinger
}) => {
  const [attendance, setAttendance] = useState(false);
  const [status, setStatus] = useState('');
  const [fingerImage, setFingerImage] = useState('');
  const [loading, setLoading] = useState(false);

  const onCaptureFinger = async () => {
    setStatus('');
    setLoading(true);
    try {
      setStatus('Checking device...');
      const connected = await Morfin.isDeviceConnected();
      console.log('Connected:', connected);

      if (!connected) {
        setStatus('Device not connected');
        setLoading(false);
        return;
      } else {
        setStatus('');
      }

      const info = await Morfin.initDevice();
      console.log('Init Info:', info);

      const result = await Morfin.autoCapture(60, 10000);
      console.log('Capture Result:', result);

      const template = await Morfin.getTemplate();
      console.log('Template:', template);
      const image = await Morfin.getImage();
      setFingerImage(image);
      setTimeout(() => {
        const payload = {
          connected: connected,
          deviceInfo: info,
          captureResult: result,
          captureTemplet: template,
          captureImage: image,
          handType: HandType,
          fingerType: FingerType,
        };
        FingerPrintEmployee(payload);
      }, 3000);
    } catch (e) {
      console.log(e);
      setLoading(false);
      setStatus('Error: ' + e.message);
    }
  };
  const init = async () => {
    await createTable(); // create DB
    await syncEmployees(token); // API → DB
  };
  // const verifyMatch = async () => {
  //   if (!template1 || !template2)
  //     return setStatus("Scan both fingers first!");

  //   const score = await Morfin.matchTemplates(template1, template2);
  //   console.log("Match Score:", score);
  //   setVerifyText(JSON.stringify(score))
  //   if (score?.score > 120) {
  //     setStatus("MATCHED: Same Finger 🎉");
  //   } else {
  //     setStatus("NOT MATCHED ❌");
  //   }
  // };

  // const handleAPI = fingData => {
  //   const payload = {
  //     thumb_template_data: fingData,
  //     user
  //   };
  //   fingerPrintAdd(payload)
  //     .then(res => {
  //       console.log(res, 'FINGER CAPTURE');

  //       setTxt(JSON.stringify(res));
  //     })
  //     .catch(err => {
  //       console.log(err, 'ERROR_FINGER_CAPTURE');
  //       setTxt(JSON.stringify(err));
  //     });
  // };

  const FingerPrintEmployee = async payload => {
    const data = {
      employee_id: userId,
      template_data: payload,
    };
    AddFingerPrint(token, data)
      .then(response => {
        console.log('GetEmployeesWithoutFingerprint', response);
        setLoading(false);
        setAttendance(true);
        setTimeout(() => {
          setLoading(false);
          setAttendance(false);
          setStatus('');
          setFingerImage('');
          init();
          onUpdatedFinger();
        }, 4000);
      })
      .catch(error => {
        console.log('error', error);
        setStatus(`Server error: ${error?.message?.toString() ?? 'Try after some time.'}`);
        setLoading(false);
        setAttendance(false);
      });
  };

  const renderBackdrop = useCallback(
    props => (
      <BottomSheetBackdrop
        {...props}
        opacity={0.5}
        appearsOnIndex={0}
        disappearsOnIndex={-1}
        onPress={() => {
          setLoading(false);
          setAttendance(false);
          setStatus('');
          setFingerImage('');
        }}
      />
    ),
    [],
  );

  return (
    <BottomSheet
      ref={sheetRef}
      index={-1}
      enablePanDownToClose={false}
      enableHandlePanningGesture={false}
      enableContentPanningGesture={false}
      snapPoints={['45%']}
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
        {attendance ? (
          <View style={styles.card}>
            <View style={styles.imageContainer}>
              <FastImage
                source={Images.FingerprintSuccess}
                defaultSource={FingerPrintSuccess}
                style={styles.image}
                resizeMode="cover"
              />
            </View>
            <CustomText
              style={[
                styles.titleSuccess,
                { color: LightThemeColors.titleColor },
              ]}
            >
              Great! Your fingerprint is added successfully.
            </CustomText>
            <Button
              label={'Go Back'}
              labelColor={Colors.white}
              backgroundColor={LightThemeColors.titleColor}
              onPress={() => {
                setLoading(false);
                setAttendance(false);
                setStatus('');
                setFingerImage('');
                onCancel();
              }}
              style={styles.button}
            />
          </View>
        ) : (
          <>
            <View style={styles.imageContainer}>
              <FastImage
                source={
                  fingerImage
                    ? {
                        uri: `data:image/png;base64,${fingerImage}`,
                        priority: FastImage.priority.high,
                      }
                    : Images.FingerPrintScan
                }
                defaultSource={FingerPrintScan}
                style={styles.image}
                resizeMode="cover"
              />
            </View>
            <CustomText
              style={[styles.title, { color: LightThemeColors.titleColor }]}
            >
              Place your finger on the scanner
            </CustomText>
            {status && (
              <CustomText style={[styles.title, { color: Colors?.error }]}>
                {status}
              </CustomText>
            )}

            {loading ? (
              <ActivityIndicator
                size="large"
                color={LightThemeColors.titleColor}
                style={{ marginRight: scale(8) }}
              />
            ) : (
              <Button
                label={'Capture Finger'}
                labelColor={Colors.white}
                backgroundColor={LightThemeColors.titleColor}
                onPress={onCaptureFinger}
                style={styles.button}
              />
            )}
          </>
        )}
      </BottomSheetView>
    </BottomSheet>
  );
};

export default AddFingerPrintBottomSheet;
