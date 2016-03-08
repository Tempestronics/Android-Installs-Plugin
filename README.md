# [Android Installs Plugin](https://github.com/Tempestronics/Android-Installs-Plugin) #
OctoberCMS plugin that enables tracking Android App installs.

The plugin is a backend component to track user installs from the Android frontend i.e. your Android application. It consists of a list controller to view the installs and details and exposes a REST API node to allow device to communicate with this plugin.

See [this demo app sample](https://github.com/Tempestronics/Android-Installs-DemoApp) to see how to write the Android app frontend code to integrate the plugin. In a nutshell, that app pushes the device data to your server where you have installed this plugin.

## User Data Fields ##
The following data is presently collected from the user device:

** Instance ID ** - This is a unique identifier to identify the installed app instance. The example I provided uses [Google’s Instance ID API](https://developers.google.com/instance-id/) but you can replace it by [UUID](https://en.wikipedia.org/wiki/Universally_unique_identifier) as described in [this article](http://android-developers.blogspot.in/2011/03/identifying-app-installations.html). I recommend you do this only if you plan to be an Android-only app. InstanceID, on the other hand, is better when you have both iOS and Android versions of the app so that you get unique IDs regardless of the platforms.

** Device ID ** - This is the [Android ID](http://developer.android.com/reference/android/provider/Settings.Secure.html#ANDROID_ID) field that uniquely identifies a device. In practice, while tracking installs this field is ignored but there are many applications where having a unique device ID is better. For example, when you plan to promote your app via referrals with an incentive and want only unique installs to gain the incentive (it wouldn’t be good to have users get the incentive multiple times by re-installing the app). The current version of plugin enforces that if a different instance ID appears but with same device ID, then it replaces the instance ID with the new one and also generates a `android.installs.resetInstall` event with the old ID.

** Manufacturer ** - The user’s device [manufacturer](http://developer.android.com/reference/android/os/Build.html#MANUFACTURER).

** Model ** - The user’s device [model](http://developer.android.com/reference/android/os/Build.html#MODEL)

## Events ##
** android.installs.newInstall ** - This event is generated every time a new user install happens.

** android.installs.resetInstall ** - This event is generated every time a user re-installs the app. This can occur due to user clearing the app cache, resetting the phone or removing and installing the app again. The old instance ID is included in the event so that any action can be performed.

## API Responses ##
The API generates a JSON response with the following fields:

** result ** - success or error based on whether the install data gets added successfully or not.

** reason ** - If result is error, then this data contains the error message. The value **duplicate** means that the entry already exists. This would occur if you use the Android app sample I provided as it makes the install request each time the app starts. A proper way to do it would be to check if a installed flag is present using SharedPreferences when the app starts--if not then make the install request and set the installed flag, otherwise ignore and start the app normally.
