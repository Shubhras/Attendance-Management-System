import React, { useRef } from 'react';
import { FlatList, View } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import MyEmployeeCard from '../../components/cards/MyEmployeeCard/index.js';
import { scale } from 'react-native-size-matters';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';
import AttendanceBottomSheet from '../../components/bottomSheet/AttendanceBottomSheet/index.js'

const data = [
  {
    EmployeeId: 'E001',
    Name: 'John Doe',
    MobileNumber: '+911234567890',
    image: 'https://surl.li/nezovl',
    status: 'Present'
  },
  {
    EmployeeId: 'E002',
    Name: 'Jane Smith',
    MobileNumber: '+919876543210',
    image: 'https://surl.li/nezovl',
    status: 'Absent'
  },
  {
    EmployeeId: 'E003',
    Name: 'Alice Johnson',
    MobileNumber: '+911122334455',
    image: 'https://surl.li/nezovl',
    status: 'Present'
  },
  {
    EmployeeId: 'E004',
    Name: 'Robert Brown',
    MobileNumber: '+919988776655',
    image: 'https://surl.li/nezovl',
    status: 'Pending'
  },
  {
    EmployeeId: 'E005',
    Name: 'Emily Davis',
    MobileNumber: '+911100223344',
    image: 'https://surl.li/nezovl',
    status: 'Half day'
  },
  {
    EmployeeId: 'E006',
    Name: 'Michael Wilson',
    MobileNumber: '+919912345678',
    image: 'https://surl.li/nezovl',
    status: 'Present'
  },
  {
    EmployeeId: 'E007',
    Name: 'Sophia Martinez',
    MobileNumber: '+911198765432',
    image: 'https://surl.li/nezovl',
    status: 'Absent'
  },
  {
    EmployeeId: 'E008',
    Name: 'William Anderson',
    MobileNumber: '+919911223344',
    image: 'https://surl.li/nezovl',
    status: 'Present'
  },
  {
    EmployeeId: 'E009',
    Name: 'Olivia Thomas',
    MobileNumber: '+911122556677',
    image: 'https://surl.li/nezovl',
    status: 'Pending'
  },
  {
    EmployeeId: 'E010',
    Name: 'James Taylor',
    MobileNumber: '+919988554433',
    image: 'https://surl.li/nezovl',
    status: 'Half day'
  },
]


const EmployeeListScreen = ({ navigation }) => {
  const bottomSheetRef = useRef(null);


  const renderItem = ({ item }) => (
    <MyEmployeeCard
      name={item.Name}
      mobileNumber={item.MobileNumber}
      employeeId={item?.EmployeeId}
      image={item?.image}
      onPress={() => { bottomSheetRef.current?.expand() }}
      status={item?.status}
    />
  );
  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
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
          data={data}
          keyExtractor={item => item.EmployeeId}
          renderItem={renderItem}
          showsVerticalScrollIndicator={false}
          contentContainerStyle={styles.contentContainerStyle}
        />
      </View>
      <AttendanceBottomSheet
        sheetRef={bottomSheetRef}
        onCancel={() => { bottomSheetRef.current?.close() }}
      />
    </CustomSafeAreaView>
  );
};

export default EmployeeListScreen;
