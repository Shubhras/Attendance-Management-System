import React, { useCallback, useEffect, useState } from 'react';
import { ActivityIndicator, FlatList, StatusBar, View } from 'react-native';
import { scale } from 'react-native-size-matters';
import { useSelector } from 'react-redux';
import {
  GetEmployeesWithoutFingerprint,
  getEmployeList,
} from '../../api/auth.js';
import Icons from '../../components/Icons/Icons.js';
import MyEmployeeCard from '../../components/cards/MyEmployeeCard/index.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import Header from '../../components/header/index.js';
import TextInput from '../../components/inputs/TextInput/index.js';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import styles from './styles.js';
import { showMessage } from 'react-native-flash-message';
import EmptyCart from '../../components/alerts/EmptyCart/index.js';
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

const FingerPrintEmployeeList = ({ navigation }) => {
  const [loading, setLoading] = useState(false);
  const [employee, setEmployee] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [search, setSearch] = useState('');
  const user = useSelector(state => state.users.users);
  const token = user?.access_token;
  console.log('token', token);

  // useEffect(() => {
  //   getEmployee(page, search, true);
  // }, []);

  useEffect(() => {
    const unsubscribe = navigation.addListener('focus', () => {
      setPage(1);
      getEmployee(1, search, true); // Always load latest list
    });

    return unsubscribe;
  }, [navigation]);

  const getEmployee = async (pageNumber, searchText, reset = false) => {
    if (loading) return;

    setLoading(true);
    try {
      const response = await getEmployeList(
        token,
        searchText,
        pageNumber,
        true,
      );
      console.log('GetEmployeesWithoutFingerprint', response);

      if (response?.status === true) {
        const newData = response?.data || [];
        const currentPage = response?.pagination?.current_page;
        const lastPage = response?.pagination?.last_page;

        // Set employees list
        if (reset) {
          setEmployee(newData);
        } else {
          setEmployee(prev => [...prev, ...newData]);
        }
        // 🚀 REAL pagination logic
        setHasMore(currentPage < lastPage);
      }
    } catch (error) {
      // showMessage({
      //   message: 'Error',
      //   description: 'Something went wrong. Please try again.',
      //   type: 'danger',
      // });
      console.log('error', error);
      setHasMore(false);
    } finally {
      setLoading(false);
    }
  };

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

  const renderItem = ({ item }) => {
    return (
      <MyEmployeeCard
        name={item?.name}
        mobileNumber={item?.mobile}
        employeeId={item?.employee_code}
        image={item?.photo}
        onPress={() =>
          navigation.navigate('EmployeeInfoScreen', {
            item,
            FirngerPrint: true,
          })
        }
      />
    );
  };

  const EmptyList = () => {
    return !loading && <EmptyCart message="No employees found" />;
  };

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
          title={'My Employee'}
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
          data={employee}
          keyExtractor={(item, index) => index.toString()}
          renderItem={renderItem}
          showsVerticalScrollIndicator={false}
          contentContainerStyle={
            employee.length === 0
              ? styles.contentContainerStyleEmpty
              : styles.contentContainerStyle
          }
          onEndReached={handleLoadMore}
          onEndReachedThreshold={0.5}
          ListFooterComponent={renderFooter}
          ListEmptyComponent={EmptyList}
          // ListEmptyComponent={
          //   !loading && (
          //     <View style={{ alignItems: 'center', marginTop: scale(180) }}>
          //       <Icons
          //         name="people-outline"
          //         iconType="Ionicons"
          //         size={scale(60)}
          //         color={LightThemeColors.titleColor}
          //       />
          //       <View style={{ height: scale(10) }} />
          //       <CustomText
          //         style={[
          //           styles.text,
          //           { color: LightThemeColors.textHighContrast },
          //         ]}
          //       >
          //         No employees found
          //       </CustomText>
          //     </View>
          //   )
          // }
        />
      </View>
    </SafeAreaView>
  );
};

export default FingerPrintEmployeeList;
