

import { Image, Pressable, View } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';
import { LightThemeColors } from '../../../config/Colors';
import { Text } from 'react-native-gesture-handler';
const AccessCard = ({ onPress, title, subtitle, icon,subtitleValue }) => {
  return (
    <Pressable style={styles.card} onPress={onPress}>
      <Image source={icon} style={styles.icon}/>
      <View style={styles.textView}>
        <CustomText numberOfLines={2} style={[styles.title, { color: LightThemeColors.textHighContrast }]}>{title}</CustomText>
         <CustomText numberOfLines={2}style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}>{subtitle} {subtitleValue}</CustomText>
      </View>
    </Pressable>
  );
};
export default AccessCard;