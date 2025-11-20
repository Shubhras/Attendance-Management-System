import { StyleSheet, Dimensions } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
  STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE,
} from '../../config/Constants';
import { Colors, LightThemeColors } from '../../config/Colors';

const windowWidth = Dimensions.get('window').width;
 
export default StyleSheet.create({
  mainWrapper: {
    paddingBottom: scale(30),
    flexGrow: 1,
    backgroundColor: Colors.white,
  },
  logoContainer: {
    alignItems: 'center',
    marginTop: scale(12),
    marginBottom: scale(6),
  },
  logoWrapper: {
    alignItems: 'center',
    justifyContent: 'center',
    height: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 1.6,
    width: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 3,
  },
  logoImage: {
    height: scale(100),
    width: scale(100),
    resizeMode: 'cover',
    borderRadius: scale(50),
    backgroundColor: '#f0f0f0',
  },
  changeText: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_SEMIBOLD
  },
  editButton: {
    position: 'absolute',
    bottom: scale(8),
    right: scale(100),
    borderRadius: scale(17),
    height: scale(34),
    width: scale(34),
    backgroundColor: Colors.grey,
    alignItems: 'center',
    justifyContent: 'center'
  },

  // rows and columns
  row: {
     justifyContent: 'space-between',
    marginTop: scale(10),
    paddingHorizontal: SCREEN_WIDTH * 0.06,
  },
  col: {
      marginBottom: scale(8),
  },
  colFull: {
    width: '88%',
    marginHorizontal: SCREEN_WIDTH * 0.06,
    marginBottom: scale(8),
  },

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
    fontSize: FONT_SIZE_XXS,
    width:scale(190),
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

  // small errors
  errorText: {
    color: '#D9534F',
    marginTop: scale(6),
    fontSize: FONT_SIZE_XS,
  },

  // buttons container
  buttonWrapperRow: {
    flexDirection: 'row',
    marginHorizontal: SCREEN_WIDTH * 0.06,
    marginTop: scale(18),
    alignItems: 'center',
    justifyContent: 'space-between',
  },

  // picker modal
  pickerOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.45)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  pickerBox: {
    width: '80%',
    backgroundColor: '#fff',
    borderRadius: scale(10),
    paddingVertical: scale(16),
    paddingHorizontal: scale(12),
  },
  pickerTitle: {
    fontSize: FONT_SIZE_MD,
    textAlign: 'center',
    marginBottom: scale(8),
  },
  pickerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: scale(12),
   },
  pickerText: {
    marginLeft: scale(12),
    fontSize: FONT_SIZE_SM,
  },

});
