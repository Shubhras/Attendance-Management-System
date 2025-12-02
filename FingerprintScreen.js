import React, {useState} from 'react';
import { View, Text, Button, NativeModules } from 'react-native';

const { SecuGenModule } = NativeModules;

export default function FingerprintScreen() {
  const [template, setTemplate] = useState(null);
  const [verifyResult, setVerifyResult] = useState(null);

  const capture = async () => {
    try {
      const tpl = await SecuGenModule.captureFingerprint();
      setTemplate(tpl);
      console.log("Captured template:", tpl);
      alert("Captured template length: " + tpl.length);
    } catch (e) {
      console.error(e);
      alert("Capture error: " + (e.message || e));
    }
  };

  const verify = async () => {
    try {
      if (!template) { alert("No stored template, capture first"); return; }
      const ok = await SecuGenModule.verify(template);
      setVerifyResult(ok ? "Matched" : "Not matched");
      alert("Verify: " + (ok ? "MATCH" : "NO MATCH"));
    } catch (e) {
      console.error(e);
      alert("Verify error: " + (e.message || e));
    }
  };

  return (
    <View style={{padding:20}}>
      <Button title="Capture Finger" onPress={capture} />
      <View style={{height:10}}/>
      <Button title="Verify (compare with stored template)" onPress={verify} />
      <View style={{height:10}}/>
      <Text>Stored template: {template ? template.slice(0,80) + '...' : 'none'}</Text>
      <Text>Verify result: {verifyResult}</Text>
    </View>
  );
}
