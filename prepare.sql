CREATE TABLE `NG_tmp` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ConnectTime` datetime NOT NULL,
  `DisconnectTime` datetime NOT NULL,
  `Duration` int NULL DEFAULT '0',
  `Username` text NOT NULL,
  `session_id` text NOT NULL
);

CREATE TABLE `NG_log` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ConnectTime` datetime NOT NULL,
  `DisconnectTime` datetime NOT NULL,
  `Duration` int NULL DEFAULT '0',
  `Username` text NOT NULL,
  `session_id` text NOT NULL
);

CREATE TABLE `NG_connect` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `DateConnect` datetime NOT NULL,
  `Username` text NOT NULL,
  `session_id` text NOT NULL
);
CREATE TABLE `NG_Disconnect` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `DateDisconnect` datetime NOT NULL,
  `Username` text NOT NULL,
  `session_id` text NOT NULL
);

