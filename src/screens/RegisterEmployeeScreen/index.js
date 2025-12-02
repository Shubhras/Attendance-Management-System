import React, { useState, useCallback } from 'react';
import {
  View,
  Image,
  ScrollView,
  Pressable,
  Alert,
  KeyboardAvoidingView,
  Modal,
  Text,
  TouchableOpacity,
  Platform,
} from 'react-native';
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
import DateTimePicker from '@react-native-community/datetimepicker';
import CustomDropdown from '../../components/CustomDropdown';

const employeeSchema = Yup.object().shape({
  name: Yup.string().trim().required('Name is required'),
  shift: Yup.string().required('Shift is required'),
  mobile: Yup.string()
    .matches(/^[0-9]{10}$/, 'Mobile number must be 10 digits')
    .required('Mobile is required'),
  joiningDate: Yup.date().nullable().required('Joining date is required'),
  workTitle: Yup.string().required('Work title is required'),
  govId: Yup.string().required('Gov ID is required'),
  dob: Yup.date()
    .nullable()
    .required('DOB is required')
    .max(new Date(), 'Date of birth cannot be in the future'),
  gender: Yup.string().required('Gender is required'),
  employeeType: Yup.string().required('Employee type is required'),
  contractor: Yup.string().required('Contractor is required'),
  machine: Yup.string().required('Machine is required'),
  salaryType: Yup.string().required('Salary type is required'),
  dailySalary: Yup.number()
    .typeError('Daily salary must be a number')
    .min(0, 'Salary must be positive')
    .required('Daily salary is required'),
  department: Yup.string().required('Department is required'),

  profileImage: Yup.string().required('Profile image is required'),
  aadhaarImage: Yup.string().required('Aadhaar card image is required'),
  photoImage: Yup.string().required('Photo image is required'),
  fingerprintImage: Yup.string().required('Fingerprint image is required'),
});

const RegisterEmployeeScreen = ({ navigation }) => {
  // local preview states (optional) - we'll still set Formik fields as source of truth
  const [profileImagePreview, setProfileImagePreview] = useState(null);
  const [aadhaarImagePreview, setAadhaarImagePreview] = useState(null);
  const [photoImagePreview, setPhotoImagePreview] = useState(null);
  const [fingerprintImagePreview, setFingerprintImagePreview] = useState(null);

  // Date picker visibility
  const [showJoiningPicker, setShowJoiningPicker] = useState(false);
  const [showDobPicker, setShowDobPicker] = useState(false);

  // pickerFor controls which field the modal will set (profile / aadhaar / photo / fingerprint)
  const [pickerFor, setPickerFor] = useState(null);
  const openPickerFor = (type) => setPickerFor(type);
  const closePicker = () => setPickerFor(null);

  // Image picker helpers (returns path or null)
  const pickImageFromGallery = useCallback(async () => {
    try {
      const image = await ImagePicker.openPicker({
        width: 800,
        height: 800,
        cropping: true,
        compressImageQuality: 0.8,
        mediaType: 'photo',
      });
      return image.path;
    } catch (err) {
      return null;
    }
  }, []);

  const pickImageFromCamera = useCallback(async () => {
    try {
      const image = await ImagePicker.openCamera({
        width: 800,
        height: 800,
        cropping: true,
        compressImageQuality: 0.8,
      });
      return image.path;
    } catch (err) {
      return null;
    }
  }, []);

  // dropdown lists
  const shifts = ['Morning', 'Evening', 'Night'];
  const genders = ['Male', 'Female', 'Other'];
  const employeeTypes = ['Permanent', 'Contract', 'Daily Wage'];
  const contractors = ['Contractor A', 'Contractor B', 'Contractor C'];
  const machines = ['Machine 1', 'Machine 2', 'Machine 3'];
  const salaryTypes = ['Daily', 'Weekly', 'Monthly'];

  // final submit
  const handleSubmitFinal = (values) => {
    // Use Formik values as source of truth (previews kept for UX)
    Alert.alert('Employee Saved', JSON.stringify(values, null, 2));
  };

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
      <Header
        back
        title="Register Employee"
        headerBg={LightThemeColors.titleColor}
        iconColor={Colors.white}
        style={{ height: scale(50) }}
      />

      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={{ flex: 1 }}
      >
        <ScrollView
          contentContainerStyle={[styles.mainWrapper, { backgroundColor: Colors.white }]}
          showsVerticalScrollIndicator={false}
        >
          <Formik
            initialValues={{
              name: '',
              shift: '',
              mobile: '',
              joiningDate: null,
              workTitle: '',
              govId: '',
              dob: null,
              gender: '',
              employeeType: '',
              contractor: '',
              machine: '',
              salaryType: '',
              dailySalary: '',
              department: '',

              profileImage: '',
              aadhaarImage: '',
              photoImage: '',
              fingerprintImage: '',
            }}
            validationSchema={employeeSchema}
            onSubmit={handleSubmitFinal}
          >
            {({ handleChange, handleBlur, handleSubmit, values, setFieldValue, errors, touched }) => {
              // onSelectFromPicker now has access to setFieldValue & values
              const onSelectFromPicker = async (mode) => {
                const setterMap = {
                  profile: (p) => {
                    setProfileImagePreview(p);
                    setFieldValue('profileImage', p, true);
                  },
                  aadhaar: (p) => {
                    setAadhaarImagePreview(p);
                    setFieldValue('aadhaarImage', p, true);
                  },
                  photo: (p) => {
                    setPhotoImagePreview(p);
                    setFieldValue('photoImage', p, true);
                  },
                  fingerprint: (p) => {
                    setFingerprintImagePreview(p);
                    setFieldValue('fingerprintImage', p, true);
                  },
                };

                const setter = setterMap[pickerFor];

                closePicker();

                if (!setter) return;

                const pickerFn = mode === 'camera' ? pickImageFromCamera : pickImageFromGallery;
                const imagePath = await pickerFn();

                if (imagePath) {
                  setter(imagePath);
                } else {
                  // user cancelled - don't change anything
                }
              };

              const handleDateChange = (dateType) => (event, selectedDate) => {
                if (dateType === 'joining') {
                  setShowJoiningPicker(false);
                  if (selectedDate) setFieldValue('joiningDate', selectedDate.toISOString(), true);
                } else if (dateType === 'dob') {
                  setShowDobPicker(false);
                  if (selectedDate) setFieldValue('dob', selectedDate.toISOString(), true);
                }
              };

              const setDropdownValue = (field) => (value) => {
                setFieldValue(field, value, true);
              };

              return (
                <>
                  {/* Profile Image */}
                  <View style={styles.logoContainer}>
                    <View style={styles.logoWrapper}>
                      <Image
                        style={styles.logoImage}
                        source={
                          values.profileImage
                            ? { uri: values.profileImage }
                            : require('../../assets/images/placeholder/profile.png')
                        }
                      />
                      <Pressable style={styles.editButton} onPress={() => { openPickerFor('profile'); }}>
                        <Icons name={'edit'} iconType={'MaterialIcons'} color={Colors.black} size={scale(18)} />
                      </Pressable>
                    </View>
                    {touched.profileImage && errors.profileImage && (
                      <Text style={styles.errorText}>{errors.profileImage}</Text>
                    )}
                  </View>

                  {/* Row 1 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <TextInput
                        label="Name"
                        placeholder="Enter name"
                        value={values.name}
                        onChangeText={handleChange('name')}
                        onBlur={handleBlur('name')}
                        errors={touched.name && errors.name}
                      />
                    </View>

                    <View style={styles.col}>
                      <CustomDropdown
                        label="Shift"
                        value={values.shift}
                        placeholder="Select Shift"
                        options={shifts}
                        onSelect={setDropdownValue('shift')}
                      />
                      {touched.shift && errors.shift ? (
                        <Text style={styles.errorText}>{errors.shift}</Text>
                      ) : null}
                    </View>
                  </View>

                  {/* Row 2 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <TextInput
                        label="Mobile"
                        placeholder="Enter mobile number"
                        keyboardType="phone-pad"
                        value={values.mobile}
                        onChangeText={handleChange('mobile')}
                        onBlur={handleBlur('mobile')}
                        errors={touched.mobile && errors.mobile}
                      />
                    </View>

                    <View style={styles.col}>
                      <Text style={styles.inputLabel}>Joining Date</Text>
                      <Pressable
                        style={[styles.dateInput, touched.joiningDate && errors.joiningDate ? { borderColor: 'red' } : null]}
                        onPress={() => setShowJoiningPicker(true)}
                      >
                        <Text style={[styles.dateText, !values.joiningDate && { color: '#9AA0A6' }]}>
                          {values.joiningDate ? new Date(values.joiningDate).toLocaleDateString() : 'dd/mm/yyyy'}
                        </Text>
                        <Icons name="calendar" iconType="Feather" size={scale(18)} color={Colors.black} />
                      </Pressable>

                      {showJoiningPicker && (
                        <DateTimePicker
                          value={values.joiningDate ? new Date(values.joiningDate) : new Date()}
                          mode="date"
                          maximumDate={new Date()}
                          onChange={handleDateChange('joining')}
                        />
                      )}
                      {touched.joiningDate && errors.joiningDate ? (
                        <Text style={styles.errorText}>{errors.joiningDate}</Text>
                      ) : null}
                    </View>
                  </View>

                  {/* Row 3 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <TextInput
                        label="Work Title / Job Title"
                        placeholder="Ex: Supervisor"
                        value={values.workTitle}
                        onChangeText={handleChange('workTitle')}
                        onBlur={handleBlur('workTitle')}
                        errors={touched.workTitle && errors.workTitle}
                      />
                    </View>

                    <View style={styles.col}>
                      <TextInput
                        label="Gov ID"
                        placeholder="Enter government ID"
                        value={values.govId}
                        onChangeText={handleChange('govId')}
                        onBlur={handleBlur('govId')}
                        errors={touched.govId && errors.govId}
                      />
                    </View>
                  </View>

                  {/* Row 4 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <Text style={styles.inputLabel}>Upload Aadhaar Card</Text>

                      {/* Preview */}
                      {values.aadhaarImage ? (
                        <Image source={{ uri: values.aadhaarImage }} style={styles.smallPreview} />
                      ) : null}

                      <View style={styles.fileRow}>
                        <Button
                          label="Choose file"
                          backgroundColor={Colors.grey}
                          labelColor={Colors.black}
                          onPress={() => openPickerFor('aadhaar')}
                        />
                        <Text style={styles.fileNameText}>
                          {values.aadhaarImage ? values.aadhaarImage.split('/').pop() : 'No file chosen'}
                        </Text>
                      </View>

                      {touched.aadhaarImage && errors.aadhaarImage && (
                        <Text style={styles.errorText}>{errors.aadhaarImage}</Text>
                      )}
                    </View>

                    <View style={styles.col}>
                      <Text style={styles.inputLabel}>DOB</Text>
                      <Pressable
                        style={[styles.dateInput, touched.dob && errors.dob ? { borderColor: 'red' } : null]}
                        onPress={() => setShowDobPicker(true)}
                      >
                        <Text style={[styles.dateText, !values.dob && { color: '#9AA0A6' }]}>
                          {values.dob ? new Date(values.dob).toLocaleDateString() : 'dd/mm/yyyy'}
                        </Text>
                        <Icons name="calendar" iconType="Feather" size={scale(18)} color={Colors.black} />
                      </Pressable>

                      {showDobPicker && (
                        <DateTimePicker
                          value={values.dob ? new Date(values.dob) : new Date()}
                          mode="date"
                          maximumDate={new Date()}
                          onChange={handleDateChange('dob')}
                        />
                      )}
                      {touched.dob && errors.dob ? (
                        <Text style={styles.errorText}>{errors.dob}</Text>
                      ) : null}
                    </View>
                  </View>

                  {/* Row 5 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <CustomDropdown
                        label="Gender"
                        value={values.gender}
                        placeholder="Select gender"
                        options={genders}
                        onSelect={setDropdownValue('gender')}
                      />
                      {touched.gender && errors.gender ? (
                        <Text style={styles.errorText}>{errors.gender}</Text>
                      ) : null}
                    </View>
                  </View>

                  {/* Row 7 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <CustomDropdown
                        label="Contractor"
                        value={values.contractor}
                        placeholder="Select contractor"
                        options={contractors}
                        onSelect={setDropdownValue('contractor')}
                      />
                      {touched.contractor && errors.contractor ? (
                        <Text style={styles.errorText}>{errors.contractor}</Text>
                      ) : null}
                    </View>

                    <View style={styles.col}>
                      <CustomDropdown
                        label="Assign Machine"
                        value={values.machine}
                        placeholder="Select machine"
                        options={machines}
                        onSelect={setDropdownValue('machine')}
                      />
                      {touched.machine && errors.machine ? (
                        <Text style={styles.errorText}>{errors.machine}</Text>
                      ) : null}
                    </View>
                  </View>

                  {/* Row 8 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <CustomDropdown
                        label="Salary Type"
                        value={values.salaryType}
                        placeholder="Select salary type"
                        options={salaryTypes}
                        onSelect={setDropdownValue('salaryType')}
                      />
                      {touched.salaryType && errors.salaryType ? (
                        <Text style={styles.errorText}>{errors.salaryType}</Text>
                      ) : null}
                    </View>

                    <View style={styles.col}>
                      <TextInput
                        label="Daily Salary (₹)"
                        placeholder="0.00"
                        keyboardType="numeric"
                        value={values.dailySalary}
                        onChangeText={handleChange('dailySalary')}
                        onBlur={handleBlur('dailySalary')}
                        errors={touched.dailySalary && errors.dailySalary}
                      />
                    </View>
                  </View>

                  {/* Row 9 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <TextInput
                        label="Company Department"
                        placeholder="Enter department"
                        value={values.department}
                        onChangeText={handleChange('department')}
                        onBlur={handleBlur('department')}
                        errors={touched.department && errors.department}
                      />
                    </View>
                  </View>

                  {/* Row 6 */}
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <Text style={styles.inputLabel}>Fingerprint</Text>

                      {/* Preview */}
                      {values.fingerprintImage ? (
                        <Image source={{ uri: values.fingerprintImage }} style={styles.smallPreview} />
                      ) : null}

                      <View style={styles.fileRow}>
                        <Button
                          label="Choose file"
                          backgroundColor={Colors.grey}
                          labelColor={Colors.black}
                          onPress={() => openPickerFor('fingerprint')}
                        />
                        <Text style={styles.fileNameText}>
                          {values.fingerprintImage ? values.fingerprintImage.split('/').pop() : 'No file chosen'}
                        </Text>
                      </View>

                      {touched.fingerprintImage && errors.fingerprintImage && (
                        <Text style={styles.errorText}>{errors.fingerprintImage}</Text>
                      )}
                    </View>

                    <View style={styles.col}>
                      <CustomDropdown
                        label="Employee Type"
                        value={values.employeeType}
                        placeholder="Select employee type"
                        options={employeeTypes}
                        onSelect={setDropdownValue('employeeType')}
                      />
                      {touched.employeeType && errors.employeeType ? (
                        <Text style={styles.errorText}>{errors.employeeType}</Text>
                      ) : null}
                    </View>
                  </View>

                  {/* Buttons */}
                  <View style={styles.buttonWrapperRow}>
                    <Button
                      label="Cancel"
                      backgroundColor="#C8CBCC"
                      labelColor={Colors.black}
                      onPress={() => navigation.goBack()}
                      style={{ flex: 1, marginRight: scale(8) }}
                    />

                    <Button
                      label="Save"
                      backgroundColor={LightThemeColors.titleColor}
                      labelColor={Colors.white}
                      onPress={handleSubmit}
                      style={{ flex: 1 }}
                    />
                  </View>

                  {/* --- Modal must be inside Formik render so it can call onSelectFromPicker --- */}
                  <Modal visible={!!pickerFor} transparent animationType="fade">
                    <View style={styles.pickerOverlay}>
                      <View style={styles.pickerBox}>
                        <Text style={styles.pickerTitle}>Select</Text>

                        <TouchableOpacity
                          style={styles.pickerRow}
                          onPress={() => onSelectFromPicker('camera')}
                        >
                          <Icons name="camera" iconType="Feather" size={20} />
                          <Text style={styles.pickerText}>Camera</Text>
                        </TouchableOpacity>

                        <TouchableOpacity
                          style={styles.pickerRow}
                          onPress={() => onSelectFromPicker('gallery')}
                        >
                          <Icons name="image" iconType="Feather" size={20} />
                          <Text style={styles.pickerText}>Gallery</Text>
                        </TouchableOpacity>

                        <TouchableOpacity
                          style={[styles.pickerRow, { justifyContent: 'center' }]}
                          onPress={closePicker}
                        >
                          <Text style={[styles.pickerText, { color: LightThemeColors.titleColor }]}>
                            Cancel
                          </Text>
                        </TouchableOpacity>
                      </View>
                    </View>
                  </Modal>
                </>
              );
            }}
          </Formik>

          <View style={{ height: scale(30) }} />
        </ScrollView>
      </KeyboardAvoidingView>
    </CustomSafeAreaView>
  );
};

export default RegisterEmployeeScreen;
