/**
 * Generated by xlatte
 */
module latte{

	/**
	 * Record for table setting
	 */
	export class Setting extends settingBase{

		//region Static

        /**
         * Buffer of settings
         * @type {{}}
         */
        private static globalBuffer: Setting[] = [];

        /**
         * Clears the buffer of loaded global settings
         */
        static clearGlobalSettingsBuffer(){
            Setting.globalBuffer = [];
        }

        /**
         * Goes to the server for a global setting. It buffers it locally.
         * @param name
         * @param callback
         */
        static getGlobalBuffered(name: string, callback: (s: Setting) => void){

            let found = Setting.globalBuffer.filter(s => s.name == name)[0];

            if(found){
                callback(found);
            }else{
                Setting.getGlobalByName(name).send((s: Setting) => {
                    Setting.globalBuffer.push(s);
                    callback(s);
                });
            }

        }

		//endregion


		//region Fields
        settingType: string;
		//endregion


		//region Methods
		/**
		 * Gets the metadata about the record
		 *
		 * @returns Object
		 */
		getMetadata():IRecordMeta {
			return {
				fields: {
					value: {
						text: strings.settingValue,
						type: <any>(this.settingType) || 'string'
					}
				}
			}
		}
		//endregion


		//region Events
		//endregion


		//region Properties
		//endregion

	}
}