import React from 'react';
import MaterialIcons from 'react-native-vector-icons/MaterialIcons';
import Entypo from 'react-native-vector-icons/Entypo';
import EvilIcons from 'react-native-vector-icons/EvilIcons';
import Feather from 'react-native-vector-icons/Feather';
import FontAwesome from 'react-native-vector-icons/FontAwesome';
import FontAwesome5 from 'react-native-vector-icons/FontAwesome5';
import FontAwesome6 from 'react-native-vector-icons/FontAwesome6';
import Fontisto from 'react-native-vector-icons/Fontisto';
import Foundation from 'react-native-vector-icons/Foundation';
import Ionicons from 'react-native-vector-icons/Ionicons';
import Octicons from 'react-native-vector-icons/Octicons';
import Zocial from 'react-native-vector-icons/Zocial';
import SimpleLineIcons from 'react-native-vector-icons/SimpleLineIcons';
import AntDesign from 'react-native-vector-icons/AntDesign';
import MaterialCommunityIcons from 'react-native-vector-icons/MaterialCommunityIcons';
import FeatherIcon from 'react-native-vector-icons/Feather';

import {
  View,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  Image,
  SafeAreaView,
  Platform,
} from 'react-native';
import {
  widthPercentageToDP as wp,
  heightPercentageToDP as hp,
} from 'react-native-responsive-screen';

function Icons({iconType, name, size, style, color, onPress, navigation}) {
  return (
    <View>
      {iconType === 'AntDesign' ? (
        <AntDesign
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Entypo' ? (
        <Entypo
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'EvilIcons' ? (
        <EvilIcons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Feather' ? (
        <Feather
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'FontAwesome' ? (
        <FontAwesome
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'FontAwesome5' ? (
        <FontAwesome5
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'FontAwesome6' ? (
        <FontAwesome6
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Fontisto' ? (
        <Fontisto
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Foundation' ? (
        <Foundation
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Ionicons' ? (
        <Ionicons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'MaterialIcons' ? (
        <MaterialIcons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'MaterialCommunityIcons' ? (
        <MaterialCommunityIcons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Octicons' ? (
        <Octicons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'Zocial' ? (
        <Zocial
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}
      {iconType === 'SimpleLineIcons' ? (
        <SimpleLineIcons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}

      {iconType === 'FeatherIcon' ? (
        <SimpleLineIcons
          name={name}
          size={size || 30}
          color={color || '#007aff'}
          style={style}
          onPress={onPress}
        />
      ) : null}

      {iconType === 'noIcon' ? <View style={{padding: hp('3')}} /> : null}
    </View>
  );
}

export default Icons;
