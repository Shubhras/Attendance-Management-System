import { Modal, Text, TouchableOpacity, View } from 'react-native';
import styles from './styles';
import React from 'react';
import Camera from '../../assets/icons/svg/Camera.svg'
import Gallery from '../../assets/icons/svg/Gallery.svg'
import { STANDARD_VECTOR_ICON_SIZE } from '../../config/Constants';

const ImagePickerModal = ({
  isVisible,
  onCameraPress,
  onGalleryPress,
  onClose,
}) => {

  return (
    <Modal
      animationType="none"
      transparent={true}
      visible={isVisible}
      onRequestClose={onClose}>
      <TouchableOpacity
        activeOpacity={1}
        onPress={onClose}
        style={styles.modalContainer}>
        <View style={styles.innerModal}>
          <View style={styles.topline} />
          <View style={styles.SvgWrapper}>
            <TouchableOpacity
              onPress={onCameraPress}
              style={{ alignItems: 'center' }}>
              <View style={styles.SvgContainer}>
                <Camera
                  width={STANDARD_VECTOR_ICON_SIZE * 1.5}
                  height={STANDARD_VECTOR_ICON_SIZE * 1.5}
                />
              </View>
              <Text style={styles.titletxt}>Open Camera</Text>
            </TouchableOpacity>
            <TouchableOpacity
              onPress={onGalleryPress}
              style={{ alignItems: 'center' }}>
              <View style={styles.SvgContainer}>
                <Gallery
                  width={STANDARD_VECTOR_ICON_SIZE * 1.5}
                  height={STANDARD_VECTOR_ICON_SIZE * 1.5}
                />
              </View>
              <Text style={styles.titletxt}>Open Gallery</Text>
            </TouchableOpacity>
          </View>
        </View>
      </TouchableOpacity>
    </Modal>
  );
};
export default React.memo(ImagePickerModal);
