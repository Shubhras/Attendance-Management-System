import FastImage from '@d11/react-native-fast-image';
import { Pressable, View } from 'react-native';
import { Colors, LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import styles from './styles';
const MyEmployeeCard = ({
  onPress,
  name,
  employeeId,
  mobileNumber,
  image,
  status,
}) => {
  const getStatus = statusValue => {
    // Convert to number for comparison
    const value = Number(statusValue);
    switch (value) {
      case 1:
        return { color: '#4CAF50', icon: 'PR' }; // Present
      case 0:
        return { color: '#CBD5E1', icon: 'PE' }; // Pending/Absent
      case 2:
        return { color: '#FDDA0D', icon: 'HD' }; // Pending/Absent
      default:
        return { color: '#CBD5E1', icon: 'PE' };
    }
  };
  const statusInfo = getStatus(status);
  const showStatus = status !== undefined && status !== null;

  return (
    <Pressable style={styles.card} onPress={onPress}>
      <FastImage
        source={
          image
            ? {
                uri: image,
                priority: FastImage.priority.high,
              }
            : require('../../../assets/images/Container.png')
        }
        style={styles.icon}
        resizeMode="cover"
      />
      <View style={styles.textView}>
        <CustomText
          numberOfLines={1}
          style={[styles.title, { color: LightThemeColors.textHighContrast }]}
        >
          {name}
        </CustomText>
        <CustomText
          style={[styles.id, { color: LightThemeColors.textLowContrast }]}
        >
          Employee Id : {employeeId}
        </CustomText>
        <CustomText
          style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}
        >
          {mobileNumber}
        </CustomText>
      </View>
      {showStatus && (
        <View
          style={[styles.statusView, { backgroundColor: statusInfo.color }]}
        >
          <CustomText style={[styles.iconText, { color: Colors.white }]}>
            {statusInfo.icon}
          </CustomText>
        </View>
      )}
    </Pressable>
  );
};
export default MyEmployeeCard;
