import React, { useState } from 'react';
import { View, Text, TouchableOpacity } from 'react-native';
import Morfin from './MorfinAuth';
import { fingerPrintAdd } from './src/api/auth';

const FingerprintScreenMantra = () => {
  const [status, setStatus] = useState('Tap button to capture');
  const [txt, setTxt] = useState('');
  const [info, setInfo] = useState('');
  const [capture, setScapture] = useState('');
  const [templet, setTemplet] = useState('');
  const [figimage, setfigImge] = useState('');

  const [template1, setTemplate1] = useState(null);
  const [template2, setTemplate2] = useState(null);
  const [verifyTxt, setVerifyText] = useState('');

  const captureTemplate1 = async () => {
    setStatus("Scan Finger 1...");
    const connected = await Morfin.isDeviceConnected();
    if (!connected) return setStatus("Device not connected");

    await Morfin.initDevice();
    await Morfin.autoCapture(60, 10000);
    const t1 = await Morfin.getTemplate();

    setTemplate1(t1);
    setStatus("Template 1 saved");
  };


  const captureTemplate2 = async () => {
    setStatus("Scan Finger 2...");
    const connected = await Morfin.isDeviceConnected();
    if (!connected) return setStatus("Device not connected");

    await Morfin.initDevice();
    await Morfin.autoCapture(60, 10000);
    const t2 = await Morfin.getTemplate();

    setTemplate2(t2);
    setStatus("Template 2 saved");
  };

  const verifyMatch = async () => {
    if (!template1 || !template2)
      return setStatus("Scan both fingers first!");

    const score = await Morfin.matchTemplates(template1, template2);
    console.log("Match Score:", score);
    setVerifyText(JSON.stringify(score))
    if (score?.score > 120) {
      setStatus("MATCHED: Same Finger 🎉");
    } else {
      setStatus("NOT MATCHED ❌");
    }
  };

  const onCapturePress = async () => {
    try {
      setStatus('Checking device...');
      const connected = await Morfin.isDeviceConnected();
      console.log('Connected:', connected);

      if (!connected) {
        setStatus('Device not connected');
        return;
      }

      setStatus('Initializing device...');
      const info = await Morfin.initDevice();
      console.log('Init Info:', info);
      setInfo(JSON.stringify(info));

      setStatus('Capturing fingerprint...');
      const result = await Morfin.autoCapture(60, 10000);
      console.log('Capture Result:', result);
      setScapture(JSON.stringify(result));

      setStatus('Getting template...');
      const template = await Morfin.getTemplate();
      console.log('Template:', template);
      setTemplet(JSON.stringify(template));

      setStatus('Getting image...');
      const image = await Morfin.getImage();
      setfigImge(JSON.stringify(image));
      console.log('Image:', image);

      setStatus('Done!');
      setTimeout(() => {
        const payload = {
          connected: connected,
          deviceInfo: info,
          captureResult: result,
          captureTemplet: template,
          captureImage: image,
        };
        handleAPI(payload);
      }, 10000);
    } catch (e) {
      console.log(e);
      setStatus('Error: ' + e.message);
    }
  };

  const handleAPI = fingData => {
    const payload = {
      thumb_template_data: fingData,
    };
    fingerPrintAdd(payload)
      .then(res => {
        console.log(res, 'FINGER CAPTURE');

        setTxt(JSON.stringify(res));
      })
      .catch(err => {
        console.log(err, 'ERROR_FINGER_CAPTURE');
        setTxt(JSON.stringify(err));
      });
  };

  const onreset = async () => {
    setStatus('');
    setInfo('');
    setScapture('');
    setTemplet('');
    setfigImge('');
    setTxt('');
    setTemplate1('');
    setTemplate2('');
    setVerifyText('')
  };
  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
         <View style={{ flex: 1, padding: 20, justifyContent: "center" }}>
         <Text style={{ textAlign: "center", marginBottom: 20, fontSize: 18 }}>
    verifyMatch: {verifyTxt}
      </Text>
      <Text style={{ textAlign: "center", marginBottom: 20, fontSize: 18 }}>
       res: {status}
      </Text>

      <TouchableOpacity 
        onPress={captureTemplate1}
        style={btn}>
        <Text style={txt}>Capture Finger 1</Text>
      </TouchableOpacity>

      <TouchableOpacity 
        onPress={captureTemplate2}
        style={[btn, { marginTop: 15 }]}>
        <Text style={txt}>Capture Finger 2</Text>
      </TouchableOpacity>

      <TouchableOpacity 
        onPress={verifyMatch}
        style={[btn, { marginTop: 25, backgroundColor: "green" }]}>
        <Text style={txt}>Verify</Text>
      </TouchableOpacity>
    </View>
      <TouchableOpacity
        onPress={onCapturePress}
        style={{
          paddingVertical: 14,
          paddingHorizontal: 24,
          backgroundColor: '#007bff',
          borderRadius: 8,
        }}
      >
        <Text style={{ color: 'white', fontSize: 18 }}>Capture Finger</Text>
      </TouchableOpacity>
      <TouchableOpacity
        onPress={onreset}
        style={{
          paddingVertical: 14,
          paddingHorizontal: 24,
          backgroundColor: '#007bff',
          borderRadius: 8,
        }}
      >
        <Text style={{ color: 'white', fontSize: 18 }}>Reset Finger</Text>
      </TouchableOpacity>
      <Text style={{ marginBottom: 20, fontSize: 18 }}>API RES: {txt}</Text>
      <Text style={{ marginBottom: 20, fontSize: 18 }}>status: {status}</Text>
      <Text style={{ marginBottom: 20, fontSize: 18 }}>info: {info}</Text>
      <Text style={{ marginBottom: 20, fontSize: 18 }}>capture: {capture}</Text>
      <Text style={{ marginBottom: 20, fontSize: 18 }}>templet: {templet}</Text>
      <Text style={{ marginBottom: 20, fontSize: 18 }}>
        figimage: {figimage}
      </Text>
    </View>
  );
};
const btn = {
    padding: 14,
    backgroundColor: "#007bff",
    borderRadius: 8,
    alignItems: "center"
  };
  const txt = { color: "white", fontSize: 18 };
export default FingerprintScreenMantra;
