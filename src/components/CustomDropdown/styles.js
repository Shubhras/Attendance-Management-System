import { StyleSheet, Dimensions } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
 } from '../../config/Constants';
import { Colors, LightThemeColors } from '../../config/Colors';

  
export default StyleSheet.create({

  // dropdown
  dropdownLabel: {
    fontSize: FONT_SIZE_SM,
    marginBottom: scale(6),
    color: Colors.textHighContrast,
  },
  dropdownBox: {
    borderWidth: 1,
    borderColor: '#E6E9EA',
    paddingHorizontal: scale(12),
    paddingVertical: scale(12),
    borderRadius: STANDARD_BORDER_RADIUS,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: Colors.white,
  },
  dropdownText: {
    fontSize: FONT_SIZE_SM,
    color: Colors.textHighContrast,
  },

  // date input
  inputLabel: {
    fontSize: FONT_SIZE_SM,
    marginBottom: scale(6),
    color: Colors.textHighContrast,
  },
  dateInput: {
    borderWidth: 1,
    borderColor: '#E6E9EA',
    paddingHorizontal: scale(12),
    paddingVertical: scale(12),
    borderRadius: STANDARD_BORDER_RADIUS,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: Colors.white,
  },
  dateText: {
    fontSize: FONT_SIZE_SM,
    color: Colors.textHighContrast,
  },

  // file row
  fileRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'flex-start',
  },
  fileNameText: {
    marginLeft: scale(10),
    color: Colors.textLowContrast,
    fontSize: FONT_SIZE_SM,
  },

  // modal (dropdown options)
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.4)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    backgroundColor: '#fff',
    paddingVertical: scale(8),
    maxHeight: '50%',
    borderTopLeftRadius: scale(12),
    borderTopRightRadius: scale(12),
  },
  modalItem: {
    paddingVertical: scale(14),
    paddingHorizontal: scale(18),
  },
  modalItemText: {
    fontSize: FONT_SIZE_SM,
  },
  modalCancel: {
    paddingVertical: scale(14),
    alignItems: 'center',
  },
  modalCancelText: {
    color: LightThemeColors.titleColor,
    fontSize: FONT_SIZE_SM,
  },

  
});
