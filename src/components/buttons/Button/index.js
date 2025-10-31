import { memo } from 'react';
import { TouchableOpacity } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';

// Functional component
const Button = ({ label, labelColor, backgroundColor, style, onPress, labelText }) => {
  return (
    <TouchableOpacity
      style={[styles.button, style, { backgroundColor: backgroundColor }]}
      onPress={onPress}>
      <CustomText style={[styles.label, labelText, { color: labelColor }]}>{label}</CustomText>
    </TouchableOpacity>
  );
};

// Exporting
export default memo(Button);
