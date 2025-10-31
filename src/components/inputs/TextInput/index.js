// // Exporting
// export default memo(TextInput);
import { memo, useState } from 'react';
import {
  TextInput as RNTextInput,
  View,
 } from 'react-native';
import styles from './styles';
import { Colors } from '../../../config/Colors';
import { CustomText ,CustomTextInput} from '../../global/CustomComponents';
 
const TextInput = ({
  defaultValue,
  label,
  textContentType,
  labelColor,
  placeholder,
  backgroundColor,
  textInputValueColor,
  placeholderTextColor,
  leftIcon,
  countrycode,
  countrylabelColor,
  rightIcon,
  numberOfLines,
  value,
  styleInput,
  textInputWrapper,
  onChangeText,
  onBlur,
  keyboardType, 
  secureTextEntry,
  isshow,
  autoCapitalize,
  editable,
  errors,
  leftIconstyle,
  maxLength,
  multiline = false,
  errorsSuccess,
  marginTop,
  onSubmitEditing,
  autoFocus = false,
  onContentSizeChange,
  autoComplete,
  refText,
  returnKeyType = 'next',
  onFocus,
  hidePassword
}) => {
  // const [hidePassword, setHidePassword] = useState(secureTextEntry);

  return (
    <>
      {label && (
        <CustomText
          style={[
            styles.label,
            {
              color: labelColor,
              marginTop: marginTop,
              //  textAlign: language == 'SA' ? 'right' : 'left'
            },
          ]}>
          {label}
        </CustomText>
      )}
      <View
        style={[
          styles.textInputWrapper,
          textInputWrapper,
          { backgroundColor: backgroundColor },
        ]}>
        {leftIcon && (
          <View style={[styles.textInputIconWrapper, leftIconstyle]}>
            {leftIcon}
          </View>
        )}
        {countrycode && (
          <View style={styles.textCountryWrapper}>
            <CustomText style={[styles.Countrylabel, { color: countrylabelColor }]}>
              {countrycode}
            </CustomText>
          </View>
        )}

        <CustomTextInput
          ref={refText}
          placeholder={placeholder}
          style={{
            ...{
              ...styles.textInput,
              ...{
                color: textInputValueColor,
              },
            },
            ...styleInput,
          }}
          defaultValue={defaultValue}
          autoFocus={autoFocus}
          textContentType={textContentType}
          autoComplete={autoComplete}
          placeholderTextColor={placeholderTextColor}
          value={value}
          keyboardType={keyboardType}
          numberOfLines={numberOfLines}
          onChangeText={onChangeText}
          onContentSizeChange={onContentSizeChange}
          onBlur={onBlur}
          selectionColor={Colors.primary}
          autoCapitalize={autoCapitalize}
          editable={editable}
          secureTextEntry={hidePassword}
          maxLength={maxLength}
          onFocus={onFocus}
          returnKeyType={returnKeyType}
          blurOnSubmit={false}
          multiline={multiline}
          onSubmitEditing={onSubmitEditing}
        />
        {rightIcon && (
          <View style={styles.textInputIconWrapperRight}>{rightIcon}</View>
        )}

      </View>
      <View style={styles.errorContainer}>
        {errors && (
          <CustomText
            style={[
              styles.errorText,
              // {textAlign: language == 'SA' ? 'right' : 'left'},
            ]}>
            {errors}
          </CustomText>
        )}
        {errorsSuccess && (
          <CustomText
            style={[
              styles.errorTextSucess,
              // {textAlign: language == 'SA' ? 'right' : 'left'},
            ]}>
            {errorsSuccess}
          </CustomText>
        )}
      </View>
    </>
  );
};

export default memo(TextInput);
