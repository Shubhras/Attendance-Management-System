import FastImage from '@d11/react-native-fast-image';
import { Pressable, View } from 'react-native';
import { LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import styles from './styles';


const MachineCard = ({
  onPress,
  machineName,
  employeeCount,
  image,
  managerName,
}) => {
  return (
    <Pressable style={styles.card} onPress={onPress}>
      <View style={styles.imageWrapper}>
        <FastImage
          source={{
            uri: image,
            priority: FastImage.priority.high,
          }}
          style={styles.image}
          resizeMode="cover"
        />
      </View>
      <View style={styles.textView}>
        <CustomText
          numberOfLines={2}
          style={[
            styles.machineName,
            { color: LightThemeColors.textHighContrast },
          ]}
        >
          {machineName}{' '}
        </CustomText>
        <CustomText
          numberOfLines={2}
          style={[
            styles.managerName,
            { color: LightThemeColors.textHighContrast },
          ]}
        >
          {managerName?.join(', ')}{' '}
        </CustomText>
        <CustomText
          numberOfLines={1}
          style={[
            styles.employeeCount,
            { color: LightThemeColors.textLowContrast },
          ]}
        >
          Total Employee : {employeeCount ? employeeCount : 0}
        </CustomText>
      </View>
    </Pressable>
  );
};
export default MachineCard;
