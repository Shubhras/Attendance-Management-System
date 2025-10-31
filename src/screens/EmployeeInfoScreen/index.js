import React, { useEffect, useState } from 'react';
import { Image, ScrollView, View, ActivityIndicator } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import { scale } from 'react-native-size-matters';
import { CustomText } from '../../components/global/CustomComponents.js';
import Button from '../../components/buttons/Button/index.js';
import { getEmployeeInfo } from '../../api/auth.js';
import { useSelector } from 'react-redux';
import { showMessage } from 'react-native-flash-message';

const EmployeeInfoScreen = ({ navigation, route }) => {
  const { item } = route.params;
  const [loading, setLoading] = useState(false);
  const [employee, setEmployee] = useState({});
  const user = useSelector(state => state.users.users);
  const token = user?.access_token;
console.log('user222222222',item)
  useEffect(() => {
    getEmployee();
  }, []);

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
        description: error?.message || 'Something went wrong. Please try again.',
        type: 'danger',
      });
    } finally {
      setLoading(false);
    }
  };

  function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}`;
  }

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
      <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
        <Header
          back={true}
          title={'Employee Info'}
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50)}}
        />

        {/* Loader */}
        {loading ? (
          <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
            <ActivityIndicator size="large" color={LightThemeColors.titleColor} />
            <CustomText style={{ marginTop: scale(10), color: LightThemeColors.textLowContrast }}>
              Loading Employee Info...
            </CustomText>
          </View>
        ) : (
          <ScrollView showsVerticalScrollIndicator={false}>
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
                <CustomText style={[styles.name, { color: LightThemeColors.textHighContrast }]}>
                  {employee?.name}
                </CustomText>
                <CustomText style={[styles.id, { color: LightThemeColors.textLowContrast }]}>
                  Employee Id : {employee?.employee_code}
                </CustomText>
              </View>
            </View>

            {/* Employee Info Rows */}
            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Salary :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {employee?.salary_monthly} ₹
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Machine :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {employee?.machine?.name}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Work :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {employee?.work}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Shift time :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {employee?.shiftIn_time} - {employee?.shiftOut_time}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Joining Date :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {formatDate(employee?.joiningDate)}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Gender :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {employee?.gender}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>DOB :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {formatDate(employee?.dob)}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Contact No. :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  +91 {employee?.mobile}
                </CustomText>
              </View>
            </View>

            <View style={styles.row}>
              <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Employee type :</CustomText>
              <View style={styles.valueWrapper}>
                <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>
                  {employee?.employee_type}
                </CustomText>
              </View>
            </View>

            <View style={styles.buttonWrapper}>
              <Button
                label={'Attendance List'}
                labelColor={Colors.white}
                backgroundColor={LightThemeColors.titleColor}
                onPress={() => { navigation.navigate('AttendanceScreen') }}
              />
            </View>
          </ScrollView>
        )}
      </View>
    </CustomSafeAreaView>
  );
};

export default EmployeeInfoScreen;
