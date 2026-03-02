import React, { useCallback, useEffect, useRef, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  FlatList,
  StatusBar,
  View,
} from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import MyEmployeeCard from '../../components/cards/MyEmployeeCard/index.js';
import { scale } from 'react-native-size-matters';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';
import AttendanceBottomSheet from '../../components/bottomSheet/AttendanceBottomSheet/index.js';
import { useSelector } from 'react-redux';
import { getByMachineEmployeList } from '../../api/auth.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import { useFocusEffect } from '@react-navigation/native';
import { SafeAreaView } from 'react-native-safe-area-context';

// Debounce function
function debounce(func, delay) {
  let timeoutId;

  return function (...args) {
    if (timeoutId) {
      clearTimeout(timeoutId);
    }
    timeoutId = setTimeout(() => {
      func(...args);
    }, delay);
  };
}

const AttendanceEmployeeList = ({ navigation, route }) => {
  const bottomSheetRef = useRef(null);
  const { machineItem } = route.params;
  const [loading, setLoading] = useState(false);
  const [id, setId] = useState(null);
  const [attendanceEmployee, setAttendanceEmployee] = useState([]);
  const [captureFingerPrint, setCaptureFingerPrint] = useState(null);
  const [selectHand, setSelectHand] = useState(null);
  const [selectFinger, setSelectFinger] = useState(null);
  // const [reset, setReset] = useState(false);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [search, setSearch] = useState('');
  const user = useSelector(state => state.users.users);
  const token = user?.access_token;

  const getEmployee = useCallback(
    (pageNumber, searchText, reset = false) => {
      if (loading) return;
      setLoading(true);
      getByMachineEmployeList(token, machineItem?.id, searchText, pageNumber)
        .then(response => {
          console.log('AttendanceEmployeeList', response);

          // Updated code with pagination logic
          if (response?.status === 200) {
            const newData = response?.data || [];
            const currentPage = response?.pagination?.current_page;
            const lastPage = response?.pagination?.last_page;

            // Set employees list
            if (reset) {
              setAttendanceEmployee(newData);
            } else {
              setAttendanceEmployee(prev => [...prev, ...newData]);
            }
            // 🚀 REAL pagination logic
            setHasMore(currentPage < lastPage);
          }
        })
        .catch(error => {
          console.log('API Error ===>', error);
          showMessage({
            message: 'Error',
            description: 'Something went wrong. Please try again.',
            type: 'danger',
          });
        })
        .finally(() => {
          setLoading(false);
        });
    },
    [loading, token, machineItem], // 👍 dependencies added
  );

  useFocusEffect(
    useCallback(() => {
      getEmployee(page, search, true);
    }, [page, search, true]),
  );

  const handleLoadMore = () => {
    if (!loading && hasMore) {
      const nextPage = page + 1;
      setPage(nextPage);
      getEmployee(nextPage, search);
    }
  };

  const handleDebouncedChange = useCallback(
    debounce(value => {
      setPage(1);
      getEmployee(1, value, true);
    }, 2000), // Delay of 500 milliseconds
    [],
  );

  const renderFooter = () =>
    loading ? (
      <ActivityIndicator
        style={{ marginVertical: scale(10) }}
        size="large"
        color={LightThemeColors.titleColor}
      />
    ) : null;

  const AttendanceUpdateList = info => {
    // Create a new array with updated employee
    const updatedEmployees = attendanceEmployee.map(emp => {
      if (emp.id === info) {
        console.log(
          `Employee "${
            emp.name
          }" (ID: ${info}) attendance status updated from ${
            emp.attendance_status
          } to ${1}`,
        );
        return {
          ...emp,
          attendance_status: 1,
        };
      }
      return emp;
    });

    // Check if employee was found
    const found = attendanceEmployee.some(emp => emp.id === info);
    if (!found) {
      console.log(`Employee with id ${info} not found`);
    }
    setAttendanceEmployee(updatedEmployees);
    setTimeout(() => {
      bottomSheetRef.current?.close();
    }, 4000);
  };

  const renderItem = ({ item }) => (
    <MyEmployeeCard
      name={item.name}
      mobileNumber={item?.mobile}
      employeeId={item?.employee_code}
      image={item?.photo}
      slots={item?.slots}
      isSlots={true}
      onPress={() => {
        const itemCaptureFingerPrint =
          item?.fingerprint_template_data?.captureTemplet;
        if (itemCaptureFingerPrint) {
          setCaptureFingerPrint(itemCaptureFingerPrint);
          setSelectHand(item?.fingerprint_template_data?.handType);
          setSelectFinger(item?.fingerprint_template_data?.fingerType);
          setId(item?.id);
          bottomSheetRef.current?.expand();
        } else {
          Alert.alert(
            'No Fingerprint Data',
            'This employee does not have fingerprint data available.',
          );
        }
      }}
      status={item?.attendance_status}
    />
  );
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
          title={'Employees'}
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />

        <View style={styles.searchView}>
          <TextInput
            placeholder={'Search employee...'}
            backgroundColor={Colors.grey}
            textInputWrapper={styles.textInputWrapper}
            value={search}
            // onChangeText={handleSearch}
            onChangeText={value => {
              setSearch(value);
              handleDebouncedChange(value);
            }}
            rightIcon={
              <Icons
                name={'search'}
                iconType={'Ionicons'}
                size={scale(20)}
                color={Colors.black}
              />
            }
          />
        </View>
        <FlatList
          style={styles.flateList}
          data={attendanceEmployee}
          keyExtractor={item => item?.id}
          renderItem={renderItem}
          showsVerticalScrollIndicator={false}
          contentContainerStyle={styles.contentContainerStyle}
          onEndReached={handleLoadMore}
          onEndReachedThreshold={0.5}
          ListFooterComponent={renderFooter}
          ListEmptyComponent={
            !loading && (
              <View style={{ alignItems: 'center', marginTop: scale(180) }}>
                <Icons
                  name="people-outline"
                  iconType="Ionicons"
                  size={scale(60)}
                  color={LightThemeColors.titleColor}
                />
                <View style={{ height: scale(10) }} />
                <CustomText
                  style={[
                    styles.text,
                    { color: LightThemeColors.textHighContrast },
                  ]}
                >
                  No employees found
                </CustomText>
              </View>
            )
          }
        />
      </View>
      <AttendanceBottomSheet
        sheetRef={bottomSheetRef}
        captureFingerPrint={captureFingerPrint}
        userId={id}
        machineID={machineItem?.id}
        HandType={selectHand}
        FingerType={selectFinger}
        token={token}
        onCancel={() => {
          bottomSheetRef.current?.close();
        }}
        userAttendanceMark={info => {
          AttendanceUpdateList(id);
        }}
      />
    </SafeAreaView>
  );
};

export default AttendanceEmployeeList;
