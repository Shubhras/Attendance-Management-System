import BottomSheet, {
  BottomSheetBackdrop,
  BottomSheetView,
} from '@gorhom/bottom-sheet';
import React, { useCallback, useState } from 'react';
import { View, TouchableOpacity, Image } from 'react-native';
import { scale } from 'react-native-size-matters';
import styles from './styles';
import { Colors, LightThemeColors } from '../../../config/Colors';
import { CustomText } from '../../global/CustomComponents';
import Button from '../../buttons/Button';
import TextInput from '../../inputs/TextInput';
import DateTimePickerModal from 'react-native-modal-datetime-picker';

const MyExportBottomSheet = ({ sheetRef, onCancel }) => {
  const [startDate, setStartDate] = useState(null);
  const [endDate, setEndDate] = useState(null);
  const [isDatePickerVisible, setDatePickerVisibility] = useState(false);
  const [currentPicker, setCurrentPicker] = useState(null);
  const [done, setDone] = useState(false);

  const showDatePicker = (picker) => {
    setCurrentPicker(picker);
    setDatePickerVisibility(true);
  };

  const hideDatePicker = () => {
    setDatePickerVisibility(false);
    setCurrentPicker(null);
  };

  const handleConfirm = (date) => {
    if (currentPicker === 'start') {
      setStartDate(date);
    } else if (currentPicker === 'end') {
      setEndDate(date);
    }
    hideDatePicker();
  };

  const renderBackdrop = useCallback(
    (props) => (
      <BottomSheetBackdrop
        {...props}
        opacity={0.5}
        appearsOnIndex={0}
        disappearsOnIndex={-1}
      />
    ),
    []
  );

  const handleSubmit = () => {
    // Show success view
    setDone(true);

    // Automatically close bottom sheet after 3 seconds
    setTimeout(() => {
      if (onCancel) {
        onCancel();
      }
      setDone(false); // Reset for next open
      setStartDate(null);
      setEndDate(null);
    }, 1000);
  };

  return (
    <BottomSheet
      ref={sheetRef}
      index={-1}
      enablePanDownToClose={false}
      enableHandlePanningGesture={false}
      enableContentPanningGesture={false}
      snapPoints={['60%']}
      backdropComponent={renderBackdrop}
      backgroundStyle={{
        backgroundColor: '#fff',
        borderTopLeftRadius: scale(10),
        borderTopRightRadius: scale(10),
        padding: scale(16),
      }}
      handleIndicatorStyle={styles.indicator}
    >
      <BottomSheetView style={styles.mainwrapper}>
        {done ? (
          <View style={styles.card}>
            <Image
              source={require('../../../assets/images/Frame (1).png')}
              style={styles.image}
            />
            <CustomText
              style={[styles.titleSuccess, { color: LightThemeColors.titleColor }]}
            >
              Export successfully
            </CustomText>
          </View>
        ) : (
          <>
            <CustomText
              style={[styles.titlePIN, { color: LightThemeColors.titleColor }]}
            >
              Select Export Date
            </CustomText>

            <TouchableOpacity
              onPress={() => showDatePicker('start')}
              style={styles.textInputWrapper}
            >
              <TextInput
                label="Start Date"
                placeholderTextColor={Colors.greyDark}
                backgroundColor={Colors.inputBackgroundColor}
                textInputValueColor={Colors.black}
                placeholder="Enter start date"
                value={
                  startDate
                    ? startDate.toLocaleDateString('en-GB', {
                      day: 'numeric',
                      month: 'short',
                      year: 'numeric',
                    })
                    : ''
                }
                editable={false}
              />
            </TouchableOpacity>

            <TouchableOpacity
              onPress={() => showDatePicker('end')}
              style={styles.textInputWrapper}
            >
              <TextInput
                label="End Date"
                placeholderTextColor={Colors.greyDark}
                backgroundColor={Colors.inputBackgroundColor}
                textInputValueColor={Colors.black}
                placeholder="Enter end date"
                value={
                  endDate
                    ? endDate.toLocaleDateString('en-GB', {
                      day: 'numeric',
                      month: 'short',
                      year: 'numeric',
                    })
                    : ''
                }
                editable={false}
              />
            </TouchableOpacity>

            <View style={styles.buttonView}>
              <Button
                label="Submit"
                labelColor={Colors.white}
                backgroundColor={LightThemeColors.titleColor}
                style={styles.button}
                onPress={handleSubmit}
              />
            </View>
          </>
        )}

        <DateTimePickerModal
          isVisible={isDatePickerVisible}
          mode="date"
          display="spinner"
          onConfirm={handleConfirm}
          onCancel={hideDatePicker}
        />
      </BottomSheetView>
    </BottomSheet>
  );
};

export default MyExportBottomSheet;

