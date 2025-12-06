import { memo } from 'react';
import { Pressable, Text, View } from 'react-native';
import FastImage from '@d11/react-native-fast-image';
import styles from './styles';
import { Colors, LightThemeColors } from '../../../config/Colors';

// Functional component
const CouponCard = ({
  categoryImage,
  defaultSource,
  title,
  subtitle,
  onPress,
}) => {
  // Returning
  return (
    <Pressable onPress={onPress} style={[styles.card, { backgroundColor: Colors.white }]}>
      <View style={[styles.imageWrapper]}>
        <View style={[styles.brandImageWrapper, { backgroundColor: Colors.white }]}>
          <FastImage
            style={styles.brandImage}
            source={categoryImage}
            defaultSource={defaultSource}
          />
        </View>
      </View>
      <View style={[styles.detailsBackgroundImageWrapper]}>
        <View style={[styles.detailsWrapper]}>
          <Text
            style={[styles.title, { color: LightThemeColors.textHighContrast }]}
          >
            {title}
          </Text>
          <Text
            style={[
              styles.validUpto,
              { color: LightThemeColors.textLowContrast },
            ]}
          >
            {subtitle}
          </Text>
        </View>
      </View>
    </Pressable>
  );
};

// Exporting
export default memo(CouponCard);

// import { Image, Pressable, View } from 'react-native';
// import styles from './styles';
// import { CustomText } from '../../global/CustomComponents';
// import { LightThemeColors } from '../../../config/Colors';
// import FastImage from '@d11/react-native-fast-image';

// const AccessCard = ({
//   onPress,
//   title,
//   subtitle,
//   icon,
//   defaultSource,
//   subtitleValue,
// }) => {
//   return (
//     <Pressable style={styles.card} onPress={onPress}>
//       <View style={styles.imageWrapper}>
//         <FastImage
//           source={icon}
//           defaultSource={defaultSource}
//           style={styles.icon}
//         />
//       </View>
//       <View style={styles.textView}>
//         <CustomText
//           numberOfLines={2}
//           style={[styles.title, { color: LightThemeColors.textHighContrast }]}
//         >
//           {title}
//         </CustomText>
//         <CustomText
//           numberOfLines={2}
//           style={[styles.subtitle, { color: LightThemeColors.textLowContrast }]}
//         >
//           {subtitle} {subtitleValue}
//         </CustomText>
//       </View>
//     </Pressable>
//   );
// };
// export default AccessCard;
