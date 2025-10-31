import { Image, Pressable, View } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';
import { Colors, LightThemeColors } from '../../../config/Colors';
import Icons from '../../Icons/Icons';
import { scale } from 'react-native-size-matters';
const ContractorCard = ({ onPress, name, contractorId, totalEmployee, image,onPressDownload }) => {

  
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
        <CustomText style={[styles.id, { color: LightThemeColors.textLowContrast }]}>Contractor Id : {contractorId}</CustomText>
        <CustomText style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}>Total Employee :{totalEmployee}</CustomText>
      </View>
      <Pressable style={[styles.downloadButton,{backgroundColor:Colors.grey}]} onPress={onPressDownload}>
        <Icons name={'download-outline'} iconType={'Ionicons'} size={scale(20)} color={Colors.black}/>
      </Pressable>
    </Pressable>
  );
};
export default ContractorCard;
