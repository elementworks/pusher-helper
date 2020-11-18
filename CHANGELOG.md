# Pusher Helper Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.0.5 - 2020-11-18
### Changed
- Change parameter name form `channel-name` to `channelName`

## 1.0.4 - 2020-11-18
### Added
- Add `sendmessage` controller action

### Changed
- Refactor `sendMessageToGlobalChannel($message)` to `sendMessageToChannel($channel, $event, $message)`

## 1.0.3 - 2020-11-10
### Added
- Add `orderBy` config setting for User query

## 1.0.2 - 2020-11-09
### Changed
- Update `composer.json` to correct version number

## 1.0.1 - 2020-11-09
### Added
- Add `getOnlineUserData()` service to return array user data for online users

## 1.0.0 - 2020-11-06
### Added
- Initial release
