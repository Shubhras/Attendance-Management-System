// src/components/CustomComponents.js
import React from 'react';
import { Text, TextInput } from 'react-native';

// Custom Text component
const CustomText = ({ allowFontScaling = false, ...props }) => (
  <Text allowFontScaling={allowFontScaling} {...props} />
);

// Custom TextInput component
const CustomTextInput = ({ allowFontScaling = false, ...props }) => (
  <TextInput allowFontScaling={allowFontScaling} {...props} />
);

export { CustomText, CustomTextInput };
