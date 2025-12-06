import FastImage from '@d11/react-native-fast-image';
import { Text, View } from 'react-native';
import Noemployee from '../../../assets/images/Animation/Noemployee.gif';
import { Colors } from '../../../config/Colors';
import { Images } from '../../../constants/images';
import styles from './styles';

// Functional component
const EmptyCart = ({ message }) => {
  return (
    <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
      <View style={styles.lottieViewWrapper}>
        {/* Image view */}
        <FastImage
          source={Images.NoEmployee}
          defaultSource={Noemployee}
          style={styles.image}
          resizeMode="contain"
        />
      </View>
      <Text style={[styles.message, { color: Colors.textHighContrast }]}>
        {message}
      </Text>
    </View>
  );
};

// Exporting
export default EmptyCart;
