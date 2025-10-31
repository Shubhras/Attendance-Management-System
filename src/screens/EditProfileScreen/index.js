import React, { useState, useCallback } from 'react';
import { View, Image, ScrollView, Pressable, Alert, KeyboardAvoidingView, Platform } from 'react-native';
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
import ImagePicker from 'react-native-image-crop-picker';
import ImagePickerModal from '../../components/ImagePickerModal/index.js'

const profileSchema = Yup.object().shape({
  firstName: Yup.string().required('First name is required'),
  lastName: Yup.string().required('Last name is required'),
  email: Yup.string().email('Invalid email').required('Email is required'),
});

const EditProfileScreen = ({ navigation }) => {
  const [profileImage, setProfileImage] = useState('https://surl.li/nezovl');
  const [isPickerVisible, setPickerVisible] = useState(false);

  const selectImage = useCallback(() => {
    setPickerVisible(true);
  }, []);

  const handleGalleryPick = useCallback(() => {
    setPickerVisible(false);
    ImagePicker.openPicker({
      width: 300,
      height: 400,
      cropping: true,
      compressImageQuality: 0.8,
      mediaType: 'photo',
    })
      .then(image => {
        setProfileImage(image.path);
      })
      .catch(error => {
        if (error.code !== 'E_PICKER_CANCELLED') console.log('Gallery Error:', error);
      });
  }, []);

  const handleCameraPick = useCallback(() => {
    setPickerVisible(false);
    ImagePicker.openCamera({
      width: 300,
      height: 400,
      cropping: true,
      compressImageQuality: 0.8,
    })
      .then(image => {
        setProfileImage(image.path);
      })
      .catch(error => {
        if (error.code !== 'E_PICKER_CANCELLED') console.log('Camera Error:', error);
      });
  }, []);

  const handleUpdateProfile = useCallback((values) => {
    console.log('Form Values:', values);
    Alert.alert('Profile Updated', JSON.stringify(values));
  }, []);

  return (
    <CustomSafeAreaView statusBarBackgroundColor="transparent" barStyle="dark-content">
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={{ flex: 1 }}
      >
        <ScrollView
          showsVerticalScrollIndicator={false}
          style={[styles.mainWrapper, { backgroundColor: Colors.white }]}
        >
          <Header
            back
            title="Edit Profile"
            iconColor={Colors.black}
            style={{ height: scale(50) }}
          />

          {/* Profile Image Section */}
          <View style={styles.logoContainer}>
            <View style={styles.logoWrapper}>
              <Image style={styles.logoImage} source={{ uri: profileImage }} />
              <Pressable style={styles.editButton} onPress={selectImage}>
                <Icons name={'edit'} iconType={'MaterialIcons'} color={Colors.black} size={scale(20)} />
              </Pressable>
            </View>
          </View>

          {/* Formik Form */}
          <Formik
            initialValues={{
              firstName: 'Maria',
              lastName: 'Rodriguez',
              email: 'maria@gmail.com',
              employeeId: 'E001',
            }}
            validationSchema={profileSchema}
            onSubmit={handleUpdateProfile}
          >
            {({ handleChange, handleBlur, handleSubmit, values, errors, touched }) => (
              <>
                <View style={styles.textInputWrapper}>
                  <TextInput
                    label="First name"
                    placeholder="Enter first name"
                    value={values.firstName}
                    onChangeText={handleChange('firstName')}
                    onBlur={handleBlur('firstName')}
                    errors={touched.firstName && errors.firstName}
                  />
                </View>

                <View style={styles.textInputWrapper}>
                  <TextInput
                    label="Last name"
                    placeholder="Enter last name"
                    value={values.lastName}
                    onChangeText={handleChange('lastName')}
                    onBlur={handleBlur('lastName')}
                    errors={touched.lastName && errors.lastName}
                  />
                </View>

                <View style={styles.textInputWrapper}>
                  <TextInput
                    label="Email"
                    placeholder="Enter email"
                    value={values.email}
                    onChangeText={handleChange('email')}
                    onBlur={handleBlur('email')}
                    errors={touched.email && errors.email}
                  />
                </View>

                <View style={styles.textInputWrapper}>
                  <TextInput
                    label="Employee ID"
                    placeholder="Enter employee id"
                    value={values.employeeId}
                    editable={false}
                  />
                </View>

                <View style={styles.buttonWrapper}>
                  <Button
                    label="Update Profile"
                    labelColor={Colors.white}
                    backgroundColor={LightThemeColors.titleColor}
                    onPress={handleSubmit}
                  />
                </View>
              </>
            )}
          </Formik>
        </ScrollView>

        {/* Image Picker Modal */}
        <ImagePickerModal
          isVisible={isPickerVisible}
          onCameraPress={handleCameraPick}
          onGalleryPress={handleGalleryPick}
          onClose={() => setPickerVisible(false)}
        />
      </KeyboardAvoidingView>
    </CustomSafeAreaView>
  );
};

export default EditProfileScreen;

