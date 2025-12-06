import { useState } from 'react';
import {
  FlatList,
  Modal,
  Pressable,
  TouchableOpacity,
  View,
} from 'react-native';
import { CustomText } from '../../components/global/CustomComponents';
import Icons from '../Icons/Icons.js';
import styles from './styles.js';
import { scale } from 'react-native-size-matters';
import { Colors } from '../../config/Colors.js';

const CustomDropdown = ({
  label = '',
  value = '',
  placeholder = '',
  options = [],
  onSelect,
}) => {
  const [open, setOpen] = useState(false);

  return (
    <>
      {label ? (
        <CustomText style={styles.dropdownLabel}>{label}</CustomText>
      ) : null}

      <Pressable style={styles.dropdownBox} onPress={() => setOpen(true)}>
        <CustomText
          style={[styles.dropdownText, !value && { color: '#9AA0A6' }]}
        >
          {value || placeholder}
        </CustomText>

        <Icons
          name="chevron-down"
          iconType="Feather"
          size={scale(18)}
          color={Colors.black}
        />
      </Pressable>

      <Modal visible={open} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <FlatList
              data={options}
              keyExtractor={(item, index) => index.toString()}
              renderItem={({ item }) => (
                <TouchableOpacity
                  style={styles.modalItem}
                  onPress={() => {
                    onSelect(item);
                    setOpen(false);
                  }}
                >
                  <CustomText style={styles.modalItemText}>{item}</CustomText>
                </TouchableOpacity>
              )}
              ListFooterComponent={
                <TouchableOpacity
                  style={styles.modalCancel}
                  onPress={() => setOpen(false)}
                >
                  <CustomText style={styles.modalCancelText}>Cancel</CustomText>
                </TouchableOpacity>
              }
            />
          </View>
        </View>
      </Modal>
    </>
  );
};

export default CustomDropdown;
