import { Image, Pressable, View } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';
import { LightThemeColors } from '../../../config/Colors';
const MachineCard = ({ onPress,
  machineName, employeeCount, image, managerName
}) => {

  return (
    <Pressable style={styles.card} onPress={onPress}>
      <View style={styles.imageWrapper}>
        <Image source={{ uri: image }} style={styles.image} />
      </View>
      <View style={styles.textView}>
        <CustomText numberOfLines={2} style={[styles.machineName, { color: LightThemeColors.textHighContrast }]}>{machineName} </CustomText>
        <CustomText numberOfLines={2} style={[styles.managerName, { color: LightThemeColors.textHighContrast }]}>{managerName?.join(', ')} </CustomText>
        <CustomText numberOfLines={1} style={[styles.employeeCount, { color: LightThemeColors.textLowContrast }]}>Total Employee : {employeeCount ? employeeCount : 0}</CustomText>
      </View>
    </Pressable>
  );
};
export default MachineCard;
