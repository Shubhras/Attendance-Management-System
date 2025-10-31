import React, { useState } from 'react';
import { View, ScrollView, KeyboardAvoidingView, Platform, TouchableOpacity } from 'react-native';
import { Formik } from 'formik';
import * as Yup from 'yup';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import { scale } from 'react-native-size-matters';
import Button from '../../components/buttons/Button/index.js';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';

const passwordSchema = Yup.object().shape({
    oldPassword: Yup.string().required('Old password is required'),
    newPassword: Yup.string().min(6, 'Minimum 6 characters').required('New password is required'),
    confirmPassword: Yup.string()
        .oneOf([Yup.ref('newPassword'), null], 'Passwords must match')
        .required('Confirm password is required'),
});

const ChangePassword = ({ navigation }) => {
    const [hideOld, setHideOld] = useState(true);
    const [hideNew, setHideNew] = useState(true);
    const [hideConfirm, setHideConfirm] = useState(true);

    const handleSubmit = (values) => {
        console.log('Password values:', values);
        // Call API here
    };

    return (
        <CustomSafeAreaView statusBarBackgroundColor="transparent" barStyle="dark-content">
            <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={{ flex: 1 }}>
                <ScrollView showsVerticalScrollIndicator={false} style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
                    <Header
                        back={true}
                        title={'Change Password'}
                        iconColor={Colors.black}
                        style={{ height: scale(50) }}
                    />

                    <Formik
                        initialValues={{ oldPassword: '', newPassword: '', confirmPassword: '' }}
                        validationSchema={passwordSchema}
                        onSubmit={handleSubmit}
                    >
                        {({ handleChange, handleBlur, handleSubmit, values, errors, touched }) => (
                            <View style={styles.container}>
                                <View style={styles.textInputWrapper}>
                                    <TextInput
                                        label="Old Password"
                                        placeholder="Enter old password"
                                        value={values.oldPassword}
                                        onChangeText={handleChange('oldPassword')}
                                        onBlur={handleBlur('oldPassword')}
                                        hidePassword={hideOld}
                                        errors={touched.oldPassword && errors.oldPassword}
                                        rightIcon={
                                            <TouchableOpacity style={{ width: scale(30) }} onPress={() => setHideOld(!hideOld)}>
                                                <Icons
                                                    name={hideOld ? 'eye-off-sharp' : 'eye'}
                                                    iconType="Ionicons"
                                                    color={Colors.black}
                                                    size={scale(20)}
                                                />
                                            </TouchableOpacity>
                                        }
                                    />
                                </View>

                                <View style={styles.textInputWrapper}>
                                    <TextInput
                                        label="New Password"
                                        placeholder="Enter new password"
                                        value={values.newPassword}
                                        onChangeText={handleChange('newPassword')}
                                        onBlur={handleBlur('newPassword')}
                                        hidePassword={hideNew}
                                        errors={touched.newPassword && errors.newPassword}
                                        rightIcon={
                                            <TouchableOpacity style={{ width: scale(30) }} onPress={() => setHideNew(!hideNew)}>
                                                <Icons
                                                    name={hideNew ? 'eye-off-sharp' : 'eye'}
                                                    iconType="Ionicons"
                                                    color={Colors.black}
                                                    size={scale(20)}
                                                />
                                            </TouchableOpacity>
                                        }
                                    />
                                </View>

                                <View style={styles.textInputWrapper}>
                                    <TextInput
                                        label="Confirm Password"
                                        placeholder="Confirm password"
                                        value={values.confirmPassword}
                                        onChangeText={handleChange('confirmPassword')}
                                        onBlur={handleBlur('confirmPassword')}
                                        hidePassword={hideConfirm}
                                        errors={touched.confirmPassword && errors.confirmPassword}
                                        rightIcon={
                                            <TouchableOpacity style={{ width: scale(30) }} onPress={() => setHideConfirm(!hideConfirm)}>
                                                <Icons
                                                    name={hideConfirm ? 'eye-off-sharp' : 'eye'}
                                                    iconType="Ionicons"
                                                    color={Colors.black}
                                                    size={scale(20)}
                                                />
                                            </TouchableOpacity>
                                        }
                                    />
                                </View>

                                <View style={styles.buttonWrapper}>
                                    <Button
                                        label="Submit"
                                        labelColor={Colors.white}
                                        backgroundColor={LightThemeColors.titleColor}
                                        onPress={handleSubmit}
                                    />
                                </View>
                            </View>
                        )}
                    </Formik>
                </ScrollView>
            </KeyboardAvoidingView>
        </CustomSafeAreaView>
    );
};

export default ChangePassword;
