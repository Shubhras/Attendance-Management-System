import React from 'react';
import { Alert, FlatList, View } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import ContractorCard from '../../components/cards/ContractorCard/index.js';
import { scale } from 'react-native-size-matters';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';

const data = [
  {
    EmployeeId: 'E001',
    Name: 'John Doe',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E002',
    Name: 'Jane Smith',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E003',
    Name: 'Alice Johnson',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E004',
    Name: 'Robert Brown',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E005',
    Name: 'Emily Davis',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E006',
    Name: 'Michael Wilson',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E007',
    Name: 'Sophia Martinez',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },
  {
    EmployeeId: 'E008',
    Name: 'William Anderson',
    totalEmployee: 10,
    image: 'https://surl.li/nezovl'

  },

];

const ContractorListScreen = ({ navigation }) => {


  const renderItem = ({ item }) => (
    <ContractorCard
      name={item.Name}
      totalEmployee={item.totalEmployee}
      contractorId={item?.EmployeeId}
      image={item?.image}
      onPressDownload={() => {
        Alert.alert(
          "Download Complete",
          `Report downloaded successfully: ${item?.Name}`,
          [
            { text: "OK", onPress: () => console.log("OK Pressed") },
          ],
          { cancelable: true }
        );
      }}
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
          title={'Contractors'}
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />

        <View style={styles.searchView}>
          <TextInput
            placeholder={'Search contractors...'}
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
    </CustomSafeAreaView>
  );
};

export default ContractorListScreen;
