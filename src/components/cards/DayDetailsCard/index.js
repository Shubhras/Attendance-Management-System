import { Image, Pressable, View } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';
import { LightThemeColors } from '../../../config/Colors';
import Icons from '../../Icons/Icons';
import { scale } from 'react-native-size-matters';
const DayDetailsCard = ({
  status,
  time_in,
  time_out,
  date,
  day,
  onPress }) => {

  const getStatusStyle = (status) => {
    switch (status?.toLowerCase()) {
      case 'present':
        return {
          color: '#4CAF50',
          icon: 'checkmark',
          iconType: 'Ionicons'
        };

      case 'absent':
        return {
          color: '#F44336',
          icon: 'close-outline',
          iconType: 'Ionicons'
        };

      case 'half day':
        return {
          color: '#FFC107',
          icon: 'exclamation',
          iconType: 'SimpleLineIcons'
        };

      case 'pending':
        return {
          color: '#CBD5E1',
          icon: 'exclamation',
          iconType: 'SimpleLineIcons'
        };

      default:
        return;
    }
  };
  const getDayOfMonth = (dateString) => {
    const date = new Date(dateString);
    return date.getDate(); // returns 1 for "2025-10-01"
  };
  return (
    <View style={[styles.cardWrapper,{borderLeftColor:getStatusStyle(status).color}]}>
    <Pressable style={styles.card} onPress={onPress}>
      <View style={styles.textView}>
        {date && day && <CustomText style={[styles.id, { color: LightThemeColors.textLowContrast }]}>{getDayOfMonth(date)} {day}</CustomText>}
        {time_in 
        // && time_out
         && <CustomText style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}>{time_in} 
          {/* - {time_out} */}
          </CustomText>}
      </View>
      <View style={styles.statusButton}>
        <Icons name={getStatusStyle(status)?.icon} iconType={getStatusStyle(status)?.iconType} color={getStatusStyle(status)?.color} size={scale(16)} />
        <CustomText style={styles.statusText}>{status}</CustomText>
      </View>
    </Pressable>
    </View>
  );
};
export default DayDetailsCard;
