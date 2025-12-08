import React, { useRef, useState } from 'react';
import {
  View,
  Image,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { useDispatch, useSelector } from 'react-redux';
import { Formik } from 'formik';
import * as Yup from 'yup';
import { scale } from 'react-native-size-matters';
import { showMessage } from 'react-native-flash-message';
import { CustomText } from '../../components/global/CustomComponents';
import TextInput from '../../components/inputs/TextInput';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView';
import Icons from '../../components/Icons/Icons';
import styles from './styles';
import { Colors, LightThemeColors } from '../../config/Colors';
import { loginUser } from '../../redux/slices/SessionUser';
import { LoginAPI } from '../../api/auth';

const LoginSchema = Yup.object().shape({
  userId: Yup.string().required('User ID is required'),
  password: Yup.string().required('Password is required'),
});

const LogInScreen = ({ navigation }) => {
  const dispatch = useDispatch();
  const passwordRef = useRef(null);
  const [hidePassword, setHidePassword] = useState(true);
  const [loading, setLoading] = useState(false);
  const user = useSelector(state => state.users.users);
  console.log('user6666', user);

  const loginUserButton = async values => {
    setLoading(true);
    const data = {
      email: values.userId.trim(),
      password: values.password,
    };
    console.log('data', data);

    try {
      const response = await LoginAPI(data);
      setLoading(false);
      console.log('response111111', response);
      if (response?.status === 200) {
        const tokenData = {
          access_token: response?.token,
          user: response?.user,
        };
        dispatch(loginUser(tokenData));
        navigation.reset({
          index: 0,
          routes: [{ name: 'HomeScreen' }],
        });
      } else {
        showMessage({
          message: 'Error',
          description: response?.message || 'Login failed',
          type: 'danger',
        });
      }
    } catch (error) {
      setLoading(false);
      console.log('error', error);
      showMessage({
        message: 'Error',
        description: error?.message || 'Something went wrong',
        type: 'danger',
      });
    }
  };

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={Colors.white}
      barStyle={'dark-content'}
      style={[styles.mainWrapper, { backgroundColor: Colors.white }]}
    >
      <ScrollView
        bounces={false}
        overScrollMode="never"
        showsVerticalScrollIndicator={false}
        contentContainerStyle={{ flexGrow: 1 }}
      >
        <KeyboardAvoidingView style={{ flex: 1 }}>
          <View style={styles.logoContainer}>
            <View style={styles.logoWrapper}>
              <Image
                style={styles.logoImage}
                source={require('../../assets/images/logo.png')}
              />
            </View>
          </View>

          <Formik
            initialValues={{ userId: '', password: '' }}
            validationSchema={LoginSchema}
            onSubmit={loginUserButton}
          >
            {({
              handleChange,
              handleBlur,
              handleSubmit,
              values,
              errors,
              touched,
            }) => (
              <>
                <View style={styles.textInputWrapper}>
                  <TextInput
                    label="User ID"
                    placeholder="Enter user id"
                    value={values.userId}
                    onChangeText={handleChange('userId')}
                    onBlur={handleBlur('userId')}
                    errors={touched.userId && errors.userId}
                    returnKeyType="next"
                    keyboardType={'email-address'}
                    autoCapitalize="none"
                    onSubmitEditing={() => passwordRef.current?.focus()}
                  />
                </View>

                <View style={styles.textInputWrapper}>
                  <TextInput
                    refText={passwordRef}
                    label="Password"
                    placeholder="Enter password"
                    value={values.password}
                    onChangeText={handleChange('password')}
                    onBlur={handleBlur('password')}
                    hidePassword={hidePassword}
                    errors={touched.password && errors.password}
                    rightIcon={
                      <TouchableOpacity
                        style={{ width: scale(40) }}
                        onPress={() => setHidePassword(!hidePassword)}
                      >
                        <Icons
                          name={hidePassword ? 'eye-off-sharp' : 'eye'}
                          iconType={'Ionicons'}
                          color={Colors.black}
                          size={scale(20)}
                        />
                      </TouchableOpacity>
                    }
                    returnKeyType="done"
                    onSubmitEditing={handleSubmit}
                  />
                </View>

                <View style={styles.buttonWrapper}>
                  <TouchableOpacity
                    style={[
                      styles.button,
                      {
                        backgroundColor: LightThemeColors.titleColor,
                        flexDirection: 'row',
                        justifyContent: 'center',
                        alignItems: 'center',
                      },
                    ]}
                    onPress={handleSubmit}
                    disabled={loading}
                  >
                    {loading ? (
                      <ActivityIndicator color={Colors.white} size="small" />
                    ) : (
                      <CustomText
                        style={[styles.label, { color: Colors.white }]}
                      >
                        Login
                      </CustomText>
                    )}
                  </TouchableOpacity>
                </View>
              </>
            )}
          </Formik>
        </KeyboardAvoidingView>
      </ScrollView>
    </CustomSafeAreaView>
  );
};

export default LogInScreen;
