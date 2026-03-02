import React, { useEffect, useRef, useState } from 'react';
import {
  ActivityIndicator,
  Image,
  ScrollView,
  StatusBar,
  View,
} from 'react-native';
import { showMessage } from 'react-native-flash-message';
import { widthPercentageToDP } from 'react-native-responsive-screen';
import { SafeAreaView } from 'react-native-safe-area-context';
import { scale } from 'react-native-size-matters';
import { useSelector } from 'react-redux';
import { getEmployeeInfo } from '../../api/auth.js';
import Button from '../../components/buttons/Button/index.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import Header from '../../components/header/index.js';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import { FONT_SIZE_XXS, POPPINS_REGULAR } from '../../config/Constants.js';
import styles from './styles.js';
import HrVerifyFingerPrintBottomSheet from '../../components/bottomSheet/HrVerifyFingerPrintBottomSheet';

const HrEmployeeInfoScreen = ({ navigation, route }) => {
  const { FirngerPrint, MyEmployee, item } = route.params;

  console.log('itemxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', item);

  const bottomSheetRef = useRef(null);
  const [loading, setLoading] = useState(false);
  const [employee, setEmployee] = useState({});
  const [selectHand, setSelectHand] = useState('Right Hand');
  const [selectFinger, setSelectFinger] = useState('Thumb');
  const user = useSelector(state => state.users.users);
  const [reset, setReset] = useState(false);
  const token = user?.access_token;
  const Hand = ['Right Hand', 'Left Hand'];
  const Finger = [
    'Thumb',
    'Index Finger',
    'Middle Finger',
    'Ring Finger',
    'Little Finger',
  ];
  const formatShiftTiming = shiftObj => {
    if (!shiftObj) return '';

    const formatTime = time => {
      const [hour, minute] = time.split(':');
      return `${hour}:${minute}`; // remove seconds
    };

    const start = formatTime(shiftObj.clock_in_time);
    const end = formatTime(shiftObj.clock_out_time);

    return `${shiftObj.shift_name} • ${start} - ${end}`;
  };

  useEffect(() => {
    const unsubscribe = navigation.addListener('focus', () => {
      getEmployee();
    });

    return unsubscribe;
  }, [navigation]);

  const getEmployee = async () => {
    setLoading(true);
    try {
      const response = await getEmployeeInfo(token, item?.uuid);
      console.log('getEmployeeInfo', response);
      if (response?.status === true) {
        setEmployee(response?.data);
      } else {
        showMessage({
          message: 'Error',
          description: response?.message || 'Failed to fetch employee info.',
          type: 'danger',
        });
      }
    } catch (error) {
      showMessage({
        message: 'Error',
        description:
          error?.message || 'Something went wrong. Please try again.',
        type: 'danger',
      });
      console.log('error', error);
    } finally {
      setLoading(false);
    }
  };

  function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return `${String(date.getDate()).padStart(2, '0')}-${String(
      date.getMonth() + 1,
    ).padStart(2, '0')}-${date.getFullYear()}`;
  }

  return (
    <SafeAreaView
      style={[styles.mainWrapper, { backgroundColor: Colors.primary }]}
    >
      <StatusBar
        animated={true} // Animate transitions between style changes
        backgroundColor="transparent" // Make status bar transparent (requires translucent=true)
        barStyle="light-content" // Set text and icon color (light-content or dark-content)
        hidden={false} // Show or hide the status bar
        translucent={true} // Allow content to draw under the status bar
      />
      <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
        <Header
          back={true}
          title={'Employee Info'}
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />

        {/* Loader */}
        {loading ? (
          <View
            style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}
          >
            <ActivityIndicator
              size="large"
              color={LightThemeColors.titleColor}
            />
            <CustomText
              style={{
                fontFamily: POPPINS_REGULAR,
                fontSize: FONT_SIZE_XXS,
                marginTop: scale(10),
                color: LightThemeColors.textLowContrast,
              }}
            >
              Loading Employee Info...
            </CustomText>
          </View>
        ) : (
          <ScrollView
            bounces={false}
            overScrollMode="never"
            showsVerticalScrollIndicator={false}
            contentContainerStyle={{ paddingBottom: scale(20) }}
          >
            <View style={styles.profileWrapper}>
              <View style={styles.imageWrapper}>
                <Image
                  source={
                    employee?.photo
                      ? { uri: employee?.photo }
                      : require('../../assets/images/placeholder/Pro.jpeg')
                  }
                  style={styles.image}
                />
              </View>
              <View>
                <CustomText
                  style={[
                    styles.name,
                    { color: LightThemeColors.textHighContrast },
                  ]}
                >
                  {employee?.name}
                </CustomText>
                <CustomText
                  style={[
                    styles.id,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  Employee Id : {employee?.employee_code}
                </CustomText>
              </View>
            </View>

            {/* Employee Info Rows */}
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Salary :
              </CustomText>
              <View style={styles.valueWrapper}>
                {employee?.salary_type == 'daily' ? (
                  <CustomText
                    style={[
                      styles.value,
                      { color: LightThemeColors.textLowContrast },
                    ]}
                  >
                    ₹ {employee?.salary_daily} /{employee?.salary_type}
                  </CustomText>
                ) : (
                  <CustomText
                    style={[
                      styles.value,
                      { color: LightThemeColors.textLowContrast },
                    ]}
                  >
                    ₹ {employee?.salary_monthly} /{employee?.salary_type}
                  </CustomText>
                )}
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Machine :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {employee?.machine?.name}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Work :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {employee?.employee_work_title}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Shift time :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {formatShiftTiming(employee?.shift)}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Joining Date :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {formatDate(employee?.joining_date)}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Gender :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {employee?.gender}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                DOB :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {formatDate(employee?.dob)}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Contact No. :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  +91 {employee?.mobile}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Employee type :
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast },
                  ]}
                >
                  {employee?.employee_type}
                </CustomText>
              </View>
            </View>

            {employee?.fingerprint_template_data && (
              <View style={styles.row}>
                <CustomText
                  style={[
                    styles.title,
                    { color: LightThemeColors.textHighContrast },
                  ]}
                >
                  FingerPrint type :
                </CustomText>
                <View style={styles.valueWrapper}>
                  <CustomText
                    style={[
                      styles.value,
                      {
                        color: LightThemeColors.textLowContrast,
                        textDecorationLine: 'none',
                      },
                    ]}
                  >
                    {`${employee?.fingerprint_template_data?.handType}, ${employee?.fingerprint_template_data?.fingerType}`}
                  </CustomText>
                </View>
              </View>
            )}
            <View
              style={{
                borderBottomWidth: 1,
                borderColor: Colors.inactive,
                paddingTop: 10,
              }}
            />
            <View style={styles.row}>
              <CustomText style={[styles.title, { color: Colors.green }]}>
                Calculate & Pay
              </CustomText>
            </View>
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Total Days:
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      textDecorationLine: 'none',
                    },
                  ]}
                >
                  {employee?.salary_summary?.total_days ?? 0}
                </CustomText>
              </View>
            </View>
            {/* <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Present Days:
              </CustomText>
              <View style={styles.valueWrapper}>
              <CustomText
                  style={[
                    styles.value,
                    { color: LightThemeColors.textLowContrast, textDecorationLine: 'none',  },
                  ]}
                >
                  {employee?.salary_summary?.present ?? 0}
                </CustomText>
              </View>
            </View> */}
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Half Days:
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      textDecorationLine: 'none',
                    },
                  ]}
                >
                  {employee?.salary_summary?.half_day ?? 0}
                </CustomText>
              </View>
            </View>
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Leave Days:
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      textDecorationLine: 'none',
                    },
                  ]}
                >
                  {employee?.salary_summary?.leave ?? 0}
                </CustomText>
              </View>
            </View>
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  { color: LightThemeColors.textHighContrast },
                ]}
              >
                Absent Days:
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      textDecorationLine: 'none',
                    },
                  ]}
                >
                  {employee?.salary_summary?.absent ?? 0}
                </CustomText>
              </View>
            </View>
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  {
                    color: LightThemeColors.textHighContrast,
                    width: widthPercentageToDP('55'),
                  },
                ]}
              >
                {`This month payment:`}
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      textDecorationLine: 'none',
                      width: widthPercentageToDP('36'),
                    },
                  ]}
                >
                  ₹
                  {parseFloat(employee?.salary_summary?.gross)?.toFixed(2) ??
                    '0.00'}
                  /-
                </CustomText>
              </View>
            </View>
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  {
                    color: LightThemeColors.textHighContrast,
                    width: widthPercentageToDP('49'),
                  },
                ]}
              >
                {`Advanced Payment:`}
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      textDecorationLine: 'none',
                      width: widthPercentageToDP('40'),
                    },
                  ]}
                >
                  ₹
                  {parseFloat(
                    employee?.salary_summary?.advance_payment,
                  )?.toFixed(2) ?? '0.00'}
                  /-
                </CustomText>
              </View>
            </View>
            <View style={styles.row}>
              <CustomText
                style={[
                  styles.title,
                  {
                    color: LightThemeColors.textHighContrast,
                    width: widthPercentageToDP('45'),
                  },
                ]}
              >
                {`Total Amount Pay:`}
              </CustomText>
              <View style={styles.valueWrapper}>
                <CustomText
                  style={[
                    styles.value,
                    {
                      color: LightThemeColors.textLowContrast,
                      width: widthPercentageToDP('45'),
                    },
                  ]}
                >
                  ₹
                  {parseFloat(employee?.salary_summary?.net)?.toFixed(2) ??
                    '0.00'}
                  /-
                </CustomText>
              </View>
            </View>
            {/* {!FirngerPrint && (
              <View style={styles.buttonWrapper}>
                <Button
                  label={'Attendance List'}
                  labelColor={Colors.white}
                  backgroundColor={LightThemeColors.titleColor}
                  onPress={() => {
                    if (employee?.fingerprint_template_data) {
                      navigation.navigate('EmployeeAttendanceView', {
                        id: employee.id,
                      });
                    } else {
                      Alert.alert(
                        'No Fingerprint Found',
                        'Please add your fingerprint first, then you can view your attendance list.',
                      );
                    }
                  }}
                />
              </View>
            )} */}

            <>
              <View style={styles.buttonWrapper}>
                <Button
                  label={`Pay - ₹ ${
                    parseFloat(employee?.salary_summary?.net)?.toFixed(2) ??
                    '0.00'
                  }/- `}
                  labelColor={Colors.white}
                  backgroundColor={LightThemeColors.titleColor}
                  onPress={() => {
                    if (item?.salary_summary?.isPaid) {
                      alert('This month’s salary has already been paid.');
                      return;
                    }
                    // setReset(true)
                    bottomSheetRef.current?.expand();
                  }}
                />
              </View>
            </>
          </ScrollView>
        )}
      </View>
      <HrVerifyFingerPrintBottomSheet
        sheetRef={bottomSheetRef}
        userId={item?.id}
        // reset={reset}
        captureFingerPrint={employee?.fingerprint_template_data?.captureTemplet}
        HandType={selectHand}
        FingerType={selectFinger}
        salaryAmount={employee?.salary_summary?.net}
        machineID={employee?.machine_id}
        token={token}
        onCancel={() => {
          bottomSheetRef.current?.close();
          // setReset(false);
        }}
        onUpdatedFinger={() => {
          bottomSheetRef.current?.close();
          navigation.goBack();
        }}
      />
    </SafeAreaView>
  );
};

export default HrEmployeeInfoScreen;
