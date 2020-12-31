/*
 * @Descripttion:
 * @Author: 帅气的杜恒欧巴
 * @Date: 2020-12-10 17:49:04
 * @LastEditTime: 2020-12-14 09:57:45
 */
const parser = require('./parser');
Component({
    properties: {
        md: {
            type: String,
            value: '',
            observer() {
                this.parseMd();
            }
        },
        type: {
            type: String,
            value: 'wemark'
        },
        link: {
            type: Boolean,
            value: false
        },
        highlight: {
            type: Boolean,
            value: false
        }
    },
    data: {
        parsedData: {}
    },
    methods: {
        parseMd() {
            if (!this.data.md) return;
            var parsedData = parser.parse(this.data.md, {
                link: this.data.link,
                highlight: this.data.highlight
            });
            this.setData({ parsedData });
        },

        previewImage(e) {
            let urls = [];
            urls[0] = e.currentTarget.dataset.src;
            wx.previewImage({urls});
        }
    }
});
