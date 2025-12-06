// import BottomSheet, {
//   BottomSheetBackdrop,
//   BottomSheetView,
// } from '@gorhom/bottom-sheet';
// import React, { useCallback, useState } from 'react';
// import { View, Pressable, ActivityIndicator, Image } from 'react-native';
// import { scale } from 'react-native-size-matters';
// import styles from './styles';
// import { Colors, LightThemeColors } from '../../../config/Colors';
// import { CustomText } from '../../global/CustomComponents';
// import Button from '../../buttons/Button';
// import TextInput from '../../inputs/TextInput';

// const AttendanceBottomSheet = ({ sheetRef, onCancel }) => {
//   const [usePin, setUsePin] = useState(false)
//   const [attendance, setAttendance] = useState(false)

//   const renderBackdrop = useCallback(
//     props => (
//       <BottomSheetBackdrop
//         {...props}
//         opacity={0.5}
//         appearsOnIndex={0}
//         disappearsOnIndex={-1}
//       />
//     ),
//     []
//   );

//   return (
//     <BottomSheet
//       ref={sheetRef}
//       index={-1}
//       enablePanDownToClose={false}
//       enableHandlePanningGesture={false}
//       enableContentPanningGesture={false}
//       snapPoints={['60%']}
//       backdropComponent={renderBackdrop}
//       backgroundStyle={{
//         backgroundColor: '#fff',
//         borderTopLeftRadius: scale(10),
//         borderTopRightRadius: scale(10),
//         padding: scale(16),
//       }}
//       handleIndicatorStyle={styles.indicator}
//     >
//       <BottomSheetView style={styles.mainwrapper}>
//         {attendance ?
//           <View style={styles.card}>
//             <Image
//               source={require('../../../assets/images/Frame (1).png')}
//               style={styles.image}
//             />

//             <CustomText style={[styles.titleSuccess, { color: LightThemeColors.titleColor }]}>
//               Attendance marked successfully
//             </CustomText>
//             <CustomText style={[styles.subTitle, { color: LightThemeColors.titleColor }]}>
//               Redirecting to dashboard...
//             </CustomText>
//           </View>
//           :
//           <>
//             <Image
//               source={require('../../../assets/images/Frame.png')}
//               style={styles.image}
//             />

//             <CustomText style={[styles.title, { color: LightThemeColors.titleColor }]}>
//               Place your finger on the scanner
//             </CustomText>

//             {usePin ? <View style={styles.textInputWrapper}>
//               <TextInput
//                 placeholder="Enter user PIN"
//                 placeholderTextColor={Colors.greyDark}
//                 backgroundColor={Colors.inputBackgroundColor}
//                 textInputValueColor={Colors.black}
//                 label={'Enter user PIN'}
//               />
//             </View> : <View style={styles.scannig}>
//               <ActivityIndicator size="small" color={LightThemeColors.textLowContrast} style={{ marginRight: scale(8) }} />
//               <CustomText style={[styles.indicatorText, { color: LightThemeColors.textLowContrast }]}>Scanning...</CustomText>
//             </View>}

//             {/* Buttons */}
//             <View style={styles.buttonView}>
//               <Button label={'Cancel'}
//                 labelColor={LightThemeColors.titleColor}
//                 backgroundColor={Colors.white}
//                 onPress={onCancel}
//                 style={styles.button}
//               />
//               <Button label={usePin ? 'Submit' : 'Use PIN'}
//                 labelColor={Colors.white}
//                 backgroundColor={LightThemeColors.titleColor}
//                 onPress={() => { usePin ? setAttendance(true) : setUsePin(true) }}
//                 style={styles.button}
//               />
//             </View>
//           </>}

//       </BottomSheetView>
//     </BottomSheet>
//   );
// };

// export default AttendanceBottomSheet;

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
import { AddFingerPrint, AttendanceMark } from '../../../api/auth';
import {Images} from '../../../constants/images';

const AttendanceBottomSheet = ({
  sheetRef,
  onCancel,
  token,
  userId,
  HandType,
  FingerType,
  captureFingerPrint,
  userAttendanceMark,
}) => {
  const [attendance, setAttendance] = useState(false);
  const [status, setStatus] = useState('');
  const [wrongFinger, setWrongFinger] = useState(false)
  const [fingerImage, setFingerImage] = useState('');
  const [captureTemplet, setCaptureTemplet] = useState('');
  const [loading, setLoading] = useState(false);

  const onCaptureFinger = async () => {
    // FingerPrintEmployee();
    // return;
    setStatus('');
    setWrongFinger(false);
    setFingerImage('');
    setLoading(true);
    try {
      setStatus('Checking device...');
      const connected = await Morfin.isDeviceConnected();
      console.log('Connected:', connected);

      if (!connected) {
        setStatus('Device not connected');
        setLoading(false);
        setWrongFinger(true);
        return;
      } else {
        setStatus('');
        setWrongFinger(false);
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
        verifyMatch(template);
        setCaptureTemplet(template);
      }, 3000);
    } catch (e) {
      console.log(e);
      setLoading(false);
      setWrongFinger(true);
      setStatus('Error machine: ' + JSON.stringify(e));
    }
  };

  const verifyMatch = async template => {
    if (!template) return setStatus('Scan fingers first!');

    const score = await Morfin.matchTemplates(captureFingerPrint, template);
    console.log('Match Score:', score);

    if (score?.score > 120) {
      setWrongFinger(false);
      FingerPrintEmployee();
    } else {
      setFingerImage('');
      setStatus('NOT MATCHED ❌');
      setWrongFinger(true);
      setLoading(false);
      setAttendance(false);
    }
  };

  const FingerPrintEmployee = async payload => {
    const data = {
      date:  Date().toString(),
      employee_id: userId,
      status: 1,
      clock_in: '',
      clock_out: '',
    };
    console.log('data', data);

    AttendanceMark(token, data)
      .then(response => {
        console.log('GetEmployeesWithoutFingerprint', response);
        setLoading(false);
        setAttendance(true);
        userAttendanceMark(response?.data?.id);  
      })
      .catch(error => {
        console.log('error', error);
        setStatus('Error server: ', +error?.message);
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
              Attendance marked successfully
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
                  : wrongFinger ? Images.WrongFingerPrint : Images.FingerPrintScan
              }
              defaultSource={wrongFinger ? WrongFingerprint : FingerPrintScan}
              style={styles.image}
              resizeMode="cover"
            />
           </View>
            <CustomText
              style={[styles.title, { color: LightThemeColors.titleColor }]}
            >
              Place your finger on the scanner
            </CustomText>
            <CustomText
              style={[
                styles.fingerTitle,
                { color: LightThemeColors.titleColor },
              ]}
            >
              {`${HandType} - ${FingerType}`}
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

export default AttendanceBottomSheet;
