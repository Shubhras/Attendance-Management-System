import React, { useCallback, useEffect, useState } from 'react';
import { ActivityIndicator, FlatList, ScrollView, View } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import { scale } from 'react-native-size-matters';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';
import { SCREEN_WIDTH } from '../../config/Constants.js';
import MachineCard from '../../components/cards/MachineCard/index.js';
import { getMachines } from '../../api/auth.js';
import { useSelector } from 'react-redux';
import { showMessage } from 'react-native-flash-message';

 


const MyMachineScreen = ({ navigation, route }) => {
  const { param } = route.params || {}; 
   console.log('title', param)

   
  const [loading, setLoading] = useState(false);
  const [machines, setMachines] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [search, setSearch] = useState('');
  const user = useSelector(state => state.users.users);
  const token = user?.access_token
  console.log('token',token)

  useEffect(() => {
    GetMachines(page, search, true);
  }, []);

  const handleSearch = useCallback((text) => {
    setSearch(text);
    setPage(1);
    GetMachines(1, text, true);
  }, []);

  const GetMachines = async (pageNumber, searchText, reset = false) => {
    if (loading) return;
    setLoading(true);
    try {
      const response = await getMachines(token, searchText, pageNumber);
      console.log('getmachines',response)
      if (response?.status==200) {
        const newData = response?.data || [];
        if (reset) {
          setMachines(newData);
        } else {
          setMachines((prev) => [...prev, ...newData]);
        }
        setHasMore(newData.length > 0);
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
      GetMachines(nextPage, search);
    }
  };

  const renderFooter = () =>
    loading ? (
      <ActivityIndicator
        style={{ marginVertical: scale(10) }}
        size="small"
        color={LightThemeColors.titleColor}
      />
    ) : null;
 
  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
      <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
        <Header
          back={true}
          title={'Machines'}
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />

        <View style={styles.searchView}>
          <TextInput
            placeholder={'Search Machine...'}
            backgroundColor={Colors.grey}
            textInputWrapper={styles.textInputWrapper}
            value={search}
            onChangeText={handleSearch}
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
          data={machines}
          keyExtractor={(item) => item.id.toString()}
          numColumns={2}
          showsVerticalScrollIndicator={false}
          contentContainerStyle={{
            paddingVertical: scale(16),
            paddingHorizontal: SCREEN_WIDTH * 0.03,
          }}
          columnWrapperStyle={styles.columnWrapperStyle}
          onEndReached={handleLoadMore}
          onEndReachedThreshold={0.5}
          ListFooterComponent={renderFooter}
          renderItem={({ item }) => (
            <MachineCard
              machineName={item?.name}
              employeeCount={item?.employee_count}
              image={item?.image}
              managerName={item?.manager_names}
              onPress={() => navigation.navigate(param=='MyMachine'? 'MachineEmployeeScreen' : 'EmployeeListScreen',{machineItem:item})}
            />
          )}
        />
      </View>
    </CustomSafeAreaView>
  );
};

export default MyMachineScreen;
