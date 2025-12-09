import React, { useEffect, useRef, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  Image,
  ScrollView,
  StatusBar,
  View,
} from 'react-native';
import { showMessage } from 'react-native-flash-message';
import { scale } from 'react-native-size-matters';
import { useSelector } from 'react-redux';
import { getEmployeeInfo } from '../../api/auth.js';
import CustomDropdown from '../../components/CustomDropdown/index.js';
import AddFingerPrintBottomSheet from '../../components/bottomSheet/AddFingerPrintBottomSheet';
import Button from '../../components/buttons/Button/index.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import Header from '../../components/header/index.js';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import styles from './styles.js';
import { SafeAreaView } from 'react-native-safe-area-context';
import { FONT_SIZE_MD, FONT_SIZE_XS, FONT_SIZE_XXS, POPPINS_REGULAR } from '../../config/Constants.js';

const EmployeeInfoScreen = ({ navigation, route }) => {
  const { FirngerPrint, MyEmployee, item } = route.params;

  // console.log('itemxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', item);

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

  console.log('user222222222', item);
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
                    {employee?.salary_daily} ₹ /{employee?.salary_type}
                  </CustomText>
                ) : (
                  <CustomText
                    style={[
                      styles.value,
                      { color: LightThemeColors.textLowContrast },
                    ]}
                  >
                    {employee?.salary_monthly} ₹ /{employee?.salary_type}
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

            {!FirngerPrint && (
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
            )}

            {FirngerPrint && (
              <>
                <View style={styles.selectDropdown}>
                  <CustomDropdown
                    label="Select Hand"
                    value={selectHand}
                    placeholder="Select Hand"
                    options={Hand}
                    onSelect={value => setSelectHand(value)}
                  />
                </View>

                <View style={styles.selectDropdown}>
                  <CustomDropdown
                    label="Select Finger"
                    value={selectFinger}
                    placeholder="Select Hand"
                    options={Finger}
                    onSelect={value => setSelectFinger(value)}
                  />
                </View>

                <View style={styles.buttonWrapper}>
                  <Button
                    label={'Add FingerPrint'}
                    labelColor={Colors.white}
                    backgroundColor={LightThemeColors.titleColor}
                    onPress={() => {
                      // setReset(true)
                      bottomSheetRef.current?.expand();
                    }}
                  />
                </View>
              </>
            )}
          </ScrollView>
        )}
      </View>
      <AddFingerPrintBottomSheet
        sheetRef={bottomSheetRef}
        userId={item?.id}
        // reset={reset}
        HandType={selectHand}
        FingerType={selectFinger}
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

export default EmployeeInfoScreen;
