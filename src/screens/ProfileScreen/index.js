

import React from 'react';
import { View, Image, ScrollView, Alert } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import Header from '../../components/header/index.js';
import { scale } from 'react-native-size-matters';
import Button from '../../components/buttons/Button/index.js';
import { logoutUser } from '../../redux/slices/SessionUser.js';
import { useDispatch, useSelector } from 'react-redux';



const ProfileScreen = ({ navigation }) => {
    const dispatch = useDispatch();

    const user = useSelector(state => state.users.users?.user);

    console.log('useriiiiiiiiiii',user)
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
            { cancelable: true }
        );
    };

    return (
        <CustomSafeAreaView statusBarBackgroundColor="transparent" barStyle="dark-content">
            <ScrollView
                showsVerticalScrollIndicator={false}
                style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
                <Header
                    back={true}
                    title={'Profile'}
                    // headerBg={LightThemeColors.titleColor}
                    iconColor={Colors.black}
                    style={{ height: scale(50) }}
                />
                <View style={styles.logoContainer}>
                    <View style={styles.logoWrapper}>
                        <Image style={styles.logoImage} source={{ uri: 'https://surl.li/nezovl' }} />
                    </View>
                </View>
                <View style={styles.row}>
                    <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>First name : </CustomText>
                    <View style={styles.valueWrapper}>
                        <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>{user?.name}</CustomText>
                    </View>
                </View>
                <View style={styles.row}>
                    <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Last name : </CustomText>
                    <View style={styles.valueWrapper}>
                        <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>Rodriguez</CustomText>
                    </View>
                </View>
                <View style={styles.row}>
                    <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Employee Id : </CustomText>
                    <View style={styles.valueWrapper}>
                        <CustomText style={[styles.value, { color: LightThemeColors.textLowContrast }]}>{user?.id}</CustomText>
                    </View>
                </View>
                <View style={styles.row}>
                    <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Email : </CustomText>
                    <View style={styles.valueWrapper}>
                        <CustomText style={[styles.emailText, { color: LightThemeColors.textLowContrast }]}>{user?.email}</CustomText>
                    </View>
                </View>

                {/* <View style={styles.buttonWrapper}>
                    <Button
                        label="Edit Profile"
                        labelColor={Colors.white}
                        backgroundColor={LightThemeColors.titleColor}
                        onPress={() => { navigation.navigate('EditProfileScreen') }}
                    />
                </View>

                <View style={styles.buttonWrapper}>
                    <Button
                        label="Change Password"
                        labelColor={Colors.white}
                        backgroundColor={LightThemeColors.titleColor}
                        onPress={() => { navigation.navigate('ChangePassword') }}
                    />
                </View> */}
                <View style={styles.buttonWrapper}>
                    <Button
                        label="Log Out"
                        labelColor={Colors.white}
                        backgroundColor={LightThemeColors.titleColor}
                        onPress={() => { handleLogout() }}
                    />
                </View>

            </ScrollView>
        </CustomSafeAreaView>
    );
};

export default ProfileScreen;
