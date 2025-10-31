import { Image, Pressable, View } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';
import { Colors, LightThemeColors } from '../../../config/Colors';
const MyEmployeeCard = ({ onPress, name, employeeId, mobileNumber, image, status }) => {

  const getStatus = (status) => {
    switch (status?.toLowerCase()) {
      case 'present':
        return {
          color: '#4CAF50',
          icon: 'PR',
          
        };

      case 'absent':
        return {
          color: '#F44336',
          icon: 'A',
         
        };

      case 'half day':
        return {
          color: '#FFC107',
          icon: 'HD',
         
        };

      case 'pending':
        return {
          color: '#CBD5E1',
          icon: 'PE',
           
        };

      default:
        return;
    }
  };
  return (
    <Pressable style={styles.card} onPress={onPress}>
      <Image source={
        image
          ? { uri: image }
          :
          require('../../../assets/images/Container.png')
      } style={styles.icon} />
      <View style={styles.textView}>
        <CustomText numberOfLines={1} style={[styles.title, { color: LightThemeColors.textHighContrast }]}>{name}</CustomText>
        <CustomText style={[styles.id, { color: LightThemeColors.textLowContrast }]}>Employee Id : {employeeId}</CustomText>
        <CustomText style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}>{mobileNumber}</CustomText>
      </View>
      {status && <View style={[styles.statusView,{backgroundColor:getStatus(status)?.color}]}>
        <CustomText style={[styles.iconText,{color:Colors.white}]}>{getStatus(status)?.icon}</CustomText>
      </View>}
    </Pressable>
  );
};
export default MyEmployeeCard;
