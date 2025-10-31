import {memo} from 'react';
import {TouchableOpacity} from 'react-native';
import styles from './styles';
import Tick from '../../../assets/icons/svg/Tick.svg';
import {STANDARD_VECTOR_ICON_SIZE} from '../../../config/Constants';

// Functional component
const Checkbox = ({checked, backgroundColor}) => {
  // Returning
  return (
    <TouchableOpacity
      style={[styles.checkbox, {backgroundColor: backgroundColor}]}>
      {checked ? (
        <Tick
          width={STANDARD_VECTOR_ICON_SIZE}
          height={STANDARD_VECTOR_ICON_SIZE}
        />
      ) : null}
    </TouchableOpacity>
  );
};

// Exporting
export default memo(Checkbox);
