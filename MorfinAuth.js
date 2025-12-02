import { NativeModules } from 'react-native';
const { MorfinAuthModule } = NativeModules;

export default {
  isDeviceConnected: () => MorfinAuthModule.isDeviceConnected(),
  initDevice: () => MorfinAuthModule.initDevice(),
  autoCapture: (minQuality = 50, timeout = 10000) =>
    MorfinAuthModule.autoCapture(minQuality, timeout),
  getTemplate: () => MorfinAuthModule.getTemplate(),
  getImage: () => MorfinAuthModule.getImage(),
  matchTemplates: (t1, t2) => MorfinAuthModule.matchTemplates(t1, t2, "FMR_V2005"),

};
