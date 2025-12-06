import React, { useCallback, useEffect, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  FlatList,
  PermissionsAndroid,
  Platform,
  View,
} from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import ContractorCard from '../../components/cards/ContractorCard/index.js';
import { scale } from 'react-native-size-matters';
import TextInput from '../../components/inputs/TextInput/index.js';
import Icons from '../../components/Icons/Icons.js';
import { useSelector } from 'react-redux';
import { getContractorsApi } from '../../api/auth.js';
import { showMessage } from 'react-native-flash-message';
import { CustomText } from '../../components/global/CustomComponents.js';
import RNFetchBlob from 'rn-fetch-blob';
import { API_URL } from '../../../env.js';
import EmptyCart from '../../components/alerts/EmptyCart/index.js';

// -----------------------------------------------
//  PERMISSION
// -----------------------------------------------
const requestStoragePermission = async () => {
  try {
    let granted = false;
    const androidVersion = parseInt(Platform.Version, 10);

    if (androidVersion >= 33) {
      const permissions = await PermissionsAndroid.requestMultiple([
        PermissionsAndroid.PERMISSIONS.READ_MEDIA_IMAGES,
        PermissionsAndroid.PERMISSIONS.READ_MEDIA_VIDEO,
        PermissionsAndroid.PERMISSIONS.READ_MEDIA_AUDIO,
      ]);

      granted = Object.values(permissions).some(
        p => p === PermissionsAndroid.RESULTS.GRANTED,
      );
    } else {
      const res = await PermissionsAndroid.request(
        PermissionsAndroid.PERMISSIONS.WRITE_EXTERNAL_STORAGE,
      );
      granted = res === PermissionsAndroid.RESULTS.GRANTED;
    }

    return granted;
  } catch (error) {
    console.log(error);
    return false;
  }
};

// -----------------------------------------------
//  DOWNLOAD FUNCTION (Correct + Token supported)
// -----------------------------------------------
const downloadFile = async (url, token) => {
  const fs = RNFetchBlob.fs;

  const downloadDir = Platform.select({
    android: fs.dirs.DownloadDir,
    ios: fs.dirs.DocumentDir,
  });

  const filename = `contractor_report_${Date.now()}.pdf`;
  const filePath = `${downloadDir}/${filename}`;

  try {
    const configOptions = Platform.select({
      ios: {
        fileCache: true,
        path: filePath,
        appendExt: 'pdf',
      },
      android: {
        fileCache: true,
        path: filePath,
        appendExt: 'pdf',
        addAndroidDownloads: {
          useDownloadManager: true,
          notification: true,
          path: filePath,
          description: 'Downloading Contractor Report...',
        },
      },
    });

    const res = await RNFetchBlob.config(configOptions).fetch('GET', url, {
      Authorization: `Bearer ${token}`,
      Accept: 'application/pdf',
    });

    return res;
  } catch (error) {
    console.log('Download Error:', error);
    Alert.alert('Download Failed', error.message);
    return null;
  }
};

// -----------------------------------------------
//  MAIN SCREEN
// -----------------------------------------------
const ContractorListScreen = ({ navigation }) => {
  const [loading, setLoading] = useState(false);
  const [contractors, setContractors] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [search, setSearch] = useState('');

  const user = useSelector(state => state.users.users);
  const token = user?.access_token;

  useEffect(() => {
    getContractors(page, search, true);
  }, []);

  // -----------------------------------------------
  //  API: Get Contractors List
  // -----------------------------------------------
  const getContractors = async (pageNumber, searchText, reset = false) => {
    if (loading) return;
    setLoading(true);

    try {
      const response = await getContractorsApi(token, searchText, pageNumber);
      console.log('res', response);

      if (response?.status === true) {
        const newData = response?.data || [];

        if (reset) {
          setContractors(newData);
        } else {
          setContractors(prev => [...prev, ...newData]);
        }

        setHasMore(newData.length > 0);
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
      getContractors(nextPage, search);
    }
  };

  const handleSearch = useCallback(text => {
    setSearch(text);
    setPage(1);
    getContractors(1, text, true);
  }, []);

  // -----------------------------------------------
  //  DOWNLOAD HANDLER
  // -----------------------------------------------
  const handeleDownloadReport = async PDF_URL => {
    if (!PDF_URL) {
      Alert.alert('Error', 'Invalid download URL');
      return;
    }

    if (Platform.OS === 'android') {
      const ok = await requestStoragePermission();
      if (!ok) return;

      const res = await downloadFile(PDF_URL, token);
      if (res) Alert.alert('Download Complete', 'Saved in Downloads.');
    } else {
      const res = await downloadFile(PDF_URL, token);
      if (res) {
        Alert.alert('Download Complete', `Saved in Documents`, [
          { text: 'OK' },
          {
            text: 'Open PDF',
            onPress: () => RNFetchBlob.ios.previewDocument(res.path()),
          },
        ]);
      }
    }
  };

  // -----------------------------------------------
  //  RENDER ITEM
  // -----------------------------------------------
  const renderItem = ({ item }) => (
    <ContractorCard
      name={item?.name}
      totalEmployee={item?.employee_count}
      contractorId={item?.id}
      image={item?.self_photo}
      onPressDownload={() =>
        handeleDownloadReport(`${API_URL}/api/contractors/${item?.id}/download`)
      }
    />
  );

  const EmptyList = () => {
    return !loading && <EmptyCart message="No Contractors found" />;
  };

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
          style={styles.flateList}
          data={contractors}
          keyExtractor={(item, index) => index.toString()}
          renderItem={renderItem}
          showsVerticalScrollIndicator={false}
          contentContainerStyle={
            contractors.length === 0
              ? styles.contentContainerStyleEmpty
              : styles.contentContainerStyle
          }
          onEndReached={handleLoadMore}
          onEndReachedThreshold={0.5}
          ListFooterComponent={
            loading && (
              <ActivityIndicator
                style={{ marginVertical: scale(10) }}
                size="small"
                color={LightThemeColors.titleColor}
              />
            )
          }
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
          //       <CustomText style={[styles.text, { color: LightThemeColors.textHighContrast }]}>
          //         No Contractors found
          //       </CustomText>
          //     </View>
          //   )
          // }
        />
      </View>
    </CustomSafeAreaView>
  );
};

export default ContractorListScreen;
