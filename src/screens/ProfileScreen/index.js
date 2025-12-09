import FastImage from '@d11/react-native-fast-image';
import React from 'react';
import { Alert, ScrollView, View } from 'react-native';
import { scale } from 'react-native-size-matters';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../../components/buttons/Button/index.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import Header from '../../components/header/index.js';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import { logoutUser } from '../../redux/slices/SessionUser.js';
import styles from './styles.js';

const Local_Image =
  'https://img.freepik.com/premium-vector/user-profile-people-icon-isolated-white-background_322958-4540.jpg?semt=ais_hybrid&w=740&q=80';

const ProfileScreen = ({ navigation }) => {
  const dispatch = useDispatch();

  const user = useSelector(state => state.users.users?.user);

  console.log('useriiiiiiiiiii', user);
  const handleLogout = () => {
    Alert.alert(
      'Logout',
      'Are you sure you want to logout?',
      [
        {
          text: 'Cancel',
          style: 'cancel',
        },
        {
          text: 'Logout',
          onPress: () => {
            dispatch(logoutUser());
            navigation.reset({
              index: 0,
              routes: [{ name: 'LogInScreen' }],
            });
          },
        },
      ],
      { cancelable: true },
    );
  };

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={Colors.primary}
      barStyle="light-content"
      style={[styles.mainWrapper, { backgroundColor: Colors.primary }]}
    >
       <Header
          back={true}
          title={'Profile'}
          headerBg={Colors.primary}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />
      <ScrollView
        bounces={false}
        overScrollMode="never"
        showsVerticalScrollIndicator={false}
        contentContainerStyle={{ flexGrow: 1 }}
         style={[styles.mainWrapper, { backgroundColor: Colors.white }]}
      >
        <View style={styles.profilePhotoWrapper}>
          <FastImage
            source={user?.photo ? { uri: user?.photo } : { uri: Local_Image }}
            style={[styles.profileImage]}
            resizeMode="contain"
          />
        </View>
        <View style={styles.marginBottom} />
        <View style={styles.row}>
          <CustomText
            style={[styles.title, { color: LightThemeColors.textHighContrast }]}
          >
            Full Name :{' '}
          </CustomText>
          <View style={styles.valueWrapper}>
            <CustomText
              style={[
                styles.value,
                { color: LightThemeColors.textLowContrast },
              ]}
            >
              {user?.name}
            </CustomText>
          </View>
        </View>
        <View style={styles.row}>
          <CustomText
            style={[styles.title, { color: LightThemeColors.textHighContrast }]}
          >
            Employee Type :{' '}
          </CustomText>
          <View style={styles.valueWrapper}>
            <CustomText
              style={[
                styles.value,
                { color: LightThemeColors.textLowContrast },
              ]}
            >
              {user?.role}
            </CustomText>
          </View>
        </View>
        <View style={styles.row}>
          <CustomText
            style={[styles.title, { color: LightThemeColors.textHighContrast }]}
          >
            Employee Id :{' '}
          </CustomText>
          <View style={styles.valueWrapper}>
            <CustomText
              style={[
                styles.value,
                { color: LightThemeColors.textLowContrast },
              ]}
            >
              {user?.employee_code}
            </CustomText>
          </View>
        </View>
        <View style={styles.row}>
          <CustomText
            style={[styles.title, { color: LightThemeColors.textHighContrast }]}
          >
            Email :{' '}
          </CustomText>
          <View style={styles.valueWrapper}>
            <CustomText
              style={[
                styles.emailText,
                { color: LightThemeColors.textLowContrast },
              ]}
            >
              {user?.email}
            </CustomText>
          </View>
        </View>

        <View style={styles.buttonWrapper}>
          <Button
            label="Log Out"
            labelColor={Colors.white}
            backgroundColor={LightThemeColors.titleColor}
            onPress={() => {
              handleLogout();
            }}
          />
        </View>
      </ScrollView>
    </CustomSafeAreaView>
  );
};

export default ProfileScreen;
