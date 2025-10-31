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
import React, { useCallback, useState } from 'react';
import { View, ActivityIndicator, Image } from 'react-native';
import { scale } from 'react-native-size-matters';
import styles from './styles';
import { Colors, LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import Button from '../../buttons/Button';
// import TextInput from '../../inputs/TextInput'; // ❌ Commented: PIN input not needed

const AttendanceBottomSheet = ({ sheetRef, onCancel }) => {
  // const [usePin, setUsePin] = useState(false); // ❌ Commented: PIN mode not needed
  const [attendance, setAttendance] = useState(false);

  const renderBackdrop = useCallback(
    props => (
      <BottomSheetBackdrop
        {...props}
        opacity={0.5}
        appearsOnIndex={0}
        disappearsOnIndex={-1}
      />
    ),
    []
  );

  return (
    <BottomSheet
      ref={sheetRef}
      index={-1}
      enablePanDownToClose={false}
      enableHandlePanningGesture={false}
      enableContentPanningGesture={false}
      snapPoints={['60%']}
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
            <Image
              source={require('../../../assets/images/Frame (1).png')}
              style={styles.image}
            />

            <CustomText
              style={[styles.titleSuccess, { color: LightThemeColors.titleColor }]}
            >
              Attendance marked successfully
            </CustomText>
            <CustomText
              style={[styles.subTitle, { color: LightThemeColors.titleColor }]}
            >
              Redirecting to dashboard...
            </CustomText>
          </View>
        ) : (
          <>
            <Image
              source={require('../../../assets/images/Frame.png')}
              style={styles.image}
            />

            <CustomText
              style={[styles.title, { color: LightThemeColors.titleColor }]}
            >
              Place your finger on the scanner
            </CustomText>

            {/* ❌ Commented out the PIN input UI */}
            {/* {usePin ? (
              <View style={styles.textInputWrapper}>
                <TextInput
                  placeholder="Enter user PIN"
                  placeholderTextColor={Colors.greyDark}
                  backgroundColor={Colors.inputBackgroundColor}
                  textInputValueColor={Colors.black}
                  label={'Enter user PIN'}
                />
              </View>
            ) : ( */}
              <View style={styles.scannig}>
                <ActivityIndicator
                  size="small"
                  color={LightThemeColors.textLowContrast}
                  style={{ marginRight: scale(8) }}
                />
                <CustomText
                  style={[
                    styles.indicatorText,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  Scanning...
                </CustomText>
              </View>
            {/* )} */}

            {/* Buttons */}
            <View style={styles.buttonView}>
              <Button
                label={'Cancel'}
                labelColor={Colors.white}
                backgroundColor={LightThemeColors.titleColor}
                onPress={onCancel}
                style={styles.button}
              />
              {/* <Button
                label={'Mark Attendance'} // ✅ Changed label
                labelColor={Colors.white}
                backgroundColor={LightThemeColors.titleColor}
                onPress={() => setAttendance(true)} // ✅ Directly mark attendance
                style={styles.button}
              /> */}
            </View>
          </>
        )}
      </BottomSheetView>
    </BottomSheet>
  );
};

export default AttendanceBottomSheet;
