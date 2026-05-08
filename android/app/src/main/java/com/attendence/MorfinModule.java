// package com.attendence;

// import android.graphics.Bitmap;
// import android.graphics.BitmapFactory;
// import androidx.annotation.NonNull;

// import com.facebook.react.bridge.*;

// import com.mantra.morfinauth.DeviceInfo;
// import com.mantra.morfinauth.MorfinAuth;
// import com.mantra.morfinauth.MorfinAuth_Callback;
// import com.mantra.morfinauth.enums.DeviceModel;
// import com.mantra.morfinauth.enums.ImageFormat;
// import com.mantra.morfinauth.enums.TemplateFormat;

// public class MorfinModule extends ReactContextBaseJavaModule implements MorfinAuth_Callback {

//     private final ReactApplicationContext reactContext;
//     private MorfinAuth morfinAuth;
//     private DeviceInfo lastDeviceInfo;

//     public MorfinModule(ReactApplicationContext context) {
//         super(context);
//         this.reactContext = context;
//         morfinAuth = new MorfinAuth(context, this);
//     }

//     @NonNull
//     @Override
//     public String getName() {
//         return "MorfinAuthModule";
//     }

//     /** CHECK DEVICE CONNECTED **/
//     @ReactMethod
//     public void isDeviceConnected(Promise promise) {
//         try {
//             boolean connected = morfinAuth.IsDeviceConnected(DeviceModel.MFS500);
//             promise.resolve(connected);
//         } catch (Exception e){
//             promise.reject("error", e);
//         }
//     }

//     /** INIT DEVICE **/
//     @ReactMethod
//     public void initDevice(Promise promise) {
//         try {
//             DeviceInfo info = new DeviceInfo();
//             int ret = morfinAuth.Init(DeviceModel.MFS500, info);

//             if (ret == 0) {
//                 lastDeviceInfo = info;

//                 WritableMap map = Arguments.createMap();
//                 map.putString("make", info.Make);
//                 map.putString("model", info.Model);
//                 map.putString("serialNo", info.SerialNo);
//                 map.putInt("width", info.Width);
//                 map.putInt("height", info.Height);

//                 promise.resolve(map);
//             } else {
//                 promise.reject("INIT_FAILED", morfinAuth.GetErrorMessage(ret));
//             }
//         } catch (Exception e) {
//             promise.reject("ERROR", e);
//         }
//     }

//     /** START AUTO CAPTURE **/
//     @ReactMethod
//     public void autoCapture(int minQuality, int timeout, Promise promise) {
//         try {
//             int[] quality = new int[1];
//             int[] nfiq = new int[1];

//             int ret = morfinAuth.AutoCapture(minQuality, timeout, quality, nfiq);

//             if (ret == 0) {
//                 WritableMap map = Arguments.createMap();
//                 map.putInt("quality", quality[0]);
//                 map.putInt("nfiq", nfiq[0]);
//                 promise.resolve(map);
//             } else {
//                 promise.reject("CAPTURE_FAILED", morfinAuth.GetErrorMessage(ret));
//             }
//         } catch (Exception e) {
//             promise.reject("ERROR", e);
//         }
//     }

//     /** GET TEMPLATE **/
//     @ReactMethod
//     public void getTemplate(Promise promise) {
//         try {
//             int size = lastDeviceInfo.Width * lastDeviceInfo.Height + 1111;
//             byte[] templateBuffer = new byte[size];
//             int[] tSize = new int[size];

//             int ret = morfinAuth.GetTemplate(templateBuffer, tSize, TemplateFormat.FMR_V2005);

//             if (ret == 0) {
//                 byte[] finalData = new byte[tSize[0]];
//                 System.arraycopy(templateBuffer, 0, finalData, 0, tSize[0]);
//                 promise.resolve(finalData);
//             } else {
//                 promise.reject("TEMPLATE_ERROR", morfinAuth.GetErrorMessage(ret));
//             }
//         } catch (Exception e) {
//             promise.reject("ERROR", e);
//         }
//     }

//     /** GET IMAGE **/
//     @ReactMethod
//     public void getImage(Promise promise) {
//         try {
//             int size = lastDeviceInfo.Width * lastDeviceInfo.Height + 1111;
//             byte[] imageBuffer = new byte[size];
//             int[] tSize = new int[1];

//             int ret = morfinAuth.GetImage(imageBuffer, tSize, 1, ImageFormat.BMP);

//             if (ret == 0) {
//                 byte[] finalData = new byte[tSize[0]];
//                 System.arraycopy(imageBuffer, 0, finalData, 0, tSize[0]);

//                 String base64 = android.util.Base64.encodeToString(finalData, android.util.Base64.DEFAULT);
//                 promise.resolve(base64);
//             } else {
//                 promise.reject("IMAGE_ERROR", morfinAuth.GetErrorMessage(ret));
//             }

//         } catch (Exception e) {
//             promise.reject("ERROR", e);
//         }
//     }
// }



package com.attendence;

import android.util.Base64;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import java.io.ByteArrayOutputStream;

import com.facebook.react.bridge.*;
import com.mantra.morfinauth.DeviceInfo;
import com.mantra.morfinauth.MorfinAuth;
import com.mantra.morfinauth.MorfinAuth_Callback;
import com.mantra.morfinauth.enums.DeviceDetection;
import com.mantra.morfinauth.enums.DeviceModel;
import com.mantra.morfinauth.enums.ImageFormat;
import com.mantra.morfinauth.enums.TemplateFormat;

public class MorfinModule extends ReactContextBaseJavaModule implements MorfinAuth_Callback {

    private MorfinAuth morfinAuth;
    private DeviceInfo lastInfo;

    public MorfinModule(ReactApplicationContext context) {
        super(context);
        morfinAuth = new MorfinAuth(context, this);
    }

    @Override
    public String getName() {
        return "MorfinAuthModule";
    }

    // -------------------- API METHODS ------------------------

    @ReactMethod
    public void isDeviceConnected(Promise promise) {
        try {
            boolean connected = morfinAuth.IsDeviceConnected(DeviceModel.MFS500);
            promise.resolve(connected);
        } catch (Exception e) {
            promise.reject("ERR", e);
        }
    }

    @ReactMethod
    public void initDevice(Promise promise) {
        try {
            DeviceInfo info = new DeviceInfo();
            int ret = morfinAuth.Init(DeviceModel.MFS500, info);

            if (ret == 0) {
                lastInfo = info;

                WritableMap map = Arguments.createMap();
                map.putString("make", info.Make);
                map.putString("model", info.Model);
                map.putString("serialNo", info.SerialNo);
                map.putInt("width", info.Width);
                map.putInt("height", info.Height);

                promise.resolve(map);
            } else {
                promise.reject("INIT_FAIL", morfinAuth.GetErrorMessage(ret));
            }
        } catch (Exception e) {
            promise.reject("ERR", e);
        }
    }

    @ReactMethod
    public void autoCapture(int qualityReq, int timeout, Promise promise) {
        try {
            int[] quality = new int[1];
            int[] nfiq = new int[1];

            int ret = morfinAuth.AutoCapture(qualityReq, timeout, quality, nfiq);

            if (ret == 0) {
                WritableMap map = Arguments.createMap();
                map.putInt("quality", quality[0]);
                map.putInt("nfiq", nfiq[0]);
                promise.resolve(map);
            } else {
                promise.reject("CAPTURE_FAIL", morfinAuth.GetErrorMessage(ret));
            }
        } catch (Exception e) {
            promise.reject("ERR", e);
        }
    }

    @ReactMethod
    public void getTemplate(Promise promise) {
        try {
            if (lastInfo == null) {
                promise.reject("NO_INIT", "Device not initialized");
                return;
            }

            int size = lastInfo.Width * lastInfo.Height + 1111;
            byte[] tBuf = new byte[size];
            int[] ts = new int[size];

            int ret = morfinAuth.GetTemplate(tBuf, ts, TemplateFormat.FMR_V2005);

            if (ret == 0) {
                byte[] finalData = new byte[ts[0]];
                System.arraycopy(tBuf, 0, finalData, 0, ts[0]);

                String base64 = Base64.encodeToString(finalData, Base64.DEFAULT);
                promise.resolve(base64);
            } else {
                promise.reject("TEMPLATE_FAIL", morfinAuth.GetErrorMessage(ret));
            }
        } catch (Exception e) {
            promise.reject("ERR", e);
        }
    }

    // @ReactMethod
    // public void getImage(Promise promise) {
    //     try {
    //         if (lastInfo == null) {
    //             promise.reject("NO_INIT", "Device not initialized");
    //             return;
    //         }

    //         int size = lastInfo.Width * lastInfo.Height + 1111;
    //         byte[] imgBuf = new byte[size];
    //         int[] tSize = new int[1];

    //         int ret = morfinAuth.GetImage(imgBuf, tSize, 1, ImageFormat.BMP);

    //         if (ret == 0) {
    //             byte[] finalData = new byte[tSize[0]];
    //             System.arraycopy(imgBuf, 0, finalData, 0, tSize[0]);

    //             String base64 = Base64.encodeToString(finalData, Base64.DEFAULT);
    //             promise.resolve(base64);
    //         } else {
    //             promise.reject("IMAGE_FAIL", morfinAuth.GetErrorMessage(ret));
    //         }
    //     } catch (Exception e) {
    //         promise.reject("ERR", e);
    //     }
    // }
    @ReactMethod
    public void getImage(Promise promise) {
        try {
            if (lastInfo == null) {
                promise.reject("NO_INIT", "Device not initialized");
                return;
            }
    
            int size = lastInfo.Width * lastInfo.Height + 1111;
            byte[] imgBuf = new byte[size];
            int[] tSize = new int[1];
    
            int ret = morfinAuth.GetImage(imgBuf, tSize, 1, ImageFormat.BMP);
    
            if (ret == 0) {
                byte[] finalData = new byte[tSize[0]];
                System.arraycopy(imgBuf, 0, finalData, 0, tSize[0]);
    
                Bitmap bmp = BitmapFactory.decodeByteArray(finalData, 0, finalData.length);
    
                ByteArrayOutputStream stream = new ByteArrayOutputStream();
                bmp.compress(Bitmap.CompressFormat.PNG, 100, stream);
    
                String base64 = Base64.encodeToString(stream.toByteArray(), Base64.DEFAULT);
                promise.resolve(base64);
            } else {
                promise.reject("IMAGE_FAIL", morfinAuth.GetErrorMessage(ret));
            }
        } catch (Exception e) {
            promise.reject("ERR", e.getMessage());
        }
    }

    @ReactMethod
    public void matchTemplates(String template1Base64, String template2Base64, String format, Promise promise) {
        try {
            byte[] t1 = Base64.decode(template1Base64, Base64.DEFAULT);
            byte[] t2 = Base64.decode(template2Base64, Base64.DEFAULT);

            TemplateFormat templateFormat = TemplateFormat.FMR_V2005;
            if (format.equals("FMR_V2011")) templateFormat = TemplateFormat.FMR_V2011;
            if (format.equals("ANSI_V378")) templateFormat = TemplateFormat.ANSI_V378;

            int[] score = new int[1];

            int ret = morfinAuth.MatchTemplate(t1, t2, score, templateFormat);

            if (ret < 0) {
                promise.reject("MATCH_ERROR", morfinAuth.GetErrorMessage(ret));
                return;
            }

            WritableMap map = Arguments.createMap();
            map.putInt("score", score[0]);
            map.putBoolean("matched", score[0] >= 96);

            promise.resolve(map);

        } catch (Exception e) {
            promise.reject("MATCH_EXCEPTION", e.getMessage());
        }
    }


    @ReactMethod
    public void matchTemplatesFast(String capturedTemplate, ReadableArray templates, String format, Promise promise) {
        try {
            byte[] captured = Base64.decode(capturedTemplate, Base64.DEFAULT);

            TemplateFormat templateFormat = TemplateFormat.FMR_V2005;
            if (format.equals("FMR_V2011")) templateFormat = TemplateFormat.FMR_V2011;
            if (format.equals("ANSI_V378")) templateFormat = TemplateFormat.ANSI_V378;

            int bestScore = -1;
            int bestIndex = -1;

            for (int i = 0; i < templates.size(); i++) {
                byte[] empTemplate = Base64.decode(templates.getString(i), Base64.DEFAULT);

                int[] score = new int[1];

                int ret = morfinAuth.MatchTemplate(
                        captured,
                        empTemplate,
                        score,
                        templateFormat
                );

                if (ret >= 0 && score[0] > bestScore) {
                    bestScore = score[0];
                    bestIndex = i;
                }
            }

            WritableMap map = Arguments.createMap();
            map.putInt("score", bestScore);
            map.putInt("index", bestIndex);
            map.putBoolean("matched", bestScore >= 90);

            promise.resolve(map);

        } catch (Exception e) {
            promise.reject("MATCH_ERROR", e.getMessage());
        }
    }

    // -------------------- REQUIRED CALLBACK METHODS ------------------------

    @Override
    public void OnDeviceDetection(String message, DeviceDetection detection) {}

    @Override
    public void OnPreview(int width, int height, byte[] data) {}

    @Override
    public void OnComplete(int errorCode, int quality, int nfiq) {}

    // ❗ THIS WAS MISSING — REQUIRED BY SDK
    @Override
    public void OnFingerPosition(int positionX, int positionY) {}
}
