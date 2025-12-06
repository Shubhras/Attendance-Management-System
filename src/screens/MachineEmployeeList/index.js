import React, { useEffect, useState, useCallback } from 'react';
import { FlatList, View, ActivityIndicator } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import MyEmployeeCard from '../../components/cards/MyEmployeeCard/index.js';
import { scale } from 'react-native-size-matters';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';
import { showMessage } from 'react-native-flash-message';
import { useSelector } from 'react-redux';
import { CustomText } from '../../components/global/CustomComponents.js';
import { getByMachineEmployeList } from '../../api/auth.js';

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

const MachineEmployeeList = ({ navigation, route }) => {
  const { machineItem } = route.params;
  const [loading, setLoading] = useState(false);
  const [employee, setEmployee] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [search, setSearch] = useState('');
  const user = useSelector(state => state.users.users);
  const token = user?.access_token;

  useEffect(() => {
    getEmployee(page, search, true);
  }, []);

  const handleSearch = useCallback(text => {
    setSearch(text);
    setPage(1);
    getEmployee(1, text, true);
  }, []);

  const getEmployee = async (pageNumber, searchText, reset = false) => {
    if (loading) return;
    setLoading(true);
    try {
      const response = await getByMachineEmployeList(
        token,
        machineItem?.id,
        searchText,
        pageNumber,
      );
      console.log('getEmployee1111', response);
      // Updated code with pagination logic
      if (response?.status === 200) {
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
      showMessage({
        message: 'Error',
        description: 'Something went wrong. Please try again.',
        type: 'danger',
      });
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
        onPress={() => navigation.navigate('EmployeeInfoScreen', { item })}
      />
    );
  };
  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
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
    </CustomSafeAreaView>
  );
};

export default MachineEmployeeList;
