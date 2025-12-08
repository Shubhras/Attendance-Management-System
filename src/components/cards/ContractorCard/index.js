import FastImage from '@d11/react-native-fast-image';
import { Pressable, View } from 'react-native';
import { scale } from 'react-native-size-matters';
import { Colors, LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import Icons from '../../Icons/Icons';
import styles from './styles';

const ContractorCard = ({
  onPress,
  name,
  contractorId,
  totalEmployee,
  image,
  onPressDownload,
}) => {
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
          Contractor Id : {contractorId}
        </CustomText>
        <CustomText
          style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}
        >
          Total Employee : {totalEmployee}
        </CustomText>
      </View>
      <Pressable
        style={[styles.downloadButton, { backgroundColor: Colors.grey }]}
        onPress={onPressDownload}
      >
        <Icons
          name={'download-outline'}
          iconType={'Ionicons'}
          size={scale(20)}
          color={Colors.black}
        />
      </Pressable>
    </Pressable>
  );
};
export default ContractorCard;
