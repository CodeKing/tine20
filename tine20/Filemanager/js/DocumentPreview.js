Ext.ns('Tine.Filemanager');

Tine.Filemanager.DocumentPreview = Ext.extend(Ext.FormPanel, {
    /**
     * Node record to preview
     */
    record: null,

    /**
     * filemanager
     */
    app: null,

    /**
     * App which triggered this action
     */
    initialApp: null,

    /**
     * Required for overflow auto
     */
    autoScroll: true,

    /**
     * Overflow auto to enable scrollbar automatically
     */
    overflow: 'auto',

    /**
     * Layout
     */
    layout: 'hfit',

    /**
     * Enable scrollbar
      */
    containsScrollbar: true,

    /**
     * gray fbar
     */
    cls: 'tw-editdialog',

    initComponent: function () {
        this.addEvents(
            /**
             * Fires if no preview is available. Later it should be used to be fired if the browser is not able to load images.
             */
            'noPreviewAvailable'
        );

        this.on('noPreviewAvailable', this.onNoPreviewAvailable, this);

        if (!this.app) {
            this.app = Tine.Tinebase.appMgr.get('Filemanager');
        }

        this.action_close = new Ext.Action({
            text: this.app.i18n._('Close'),
            minWidth: 70,
            scope: this,
            handler: this.onClose,
            iconCls: 'action_cancel'
        });

        this.fbar = ['->', this.action_close];

        Ext.getBody().on('keydown', function (e) {
            if (e.getKey() === e.SPACE || e.getKey() === e.ESC) {
                this.onClose();
            }
        }, this);

        Tine.Filemanager.DocumentPreview.superclass.initComponent.apply(this, arguments);

        if (!this.record) {
            this.fireEvent('noPreviewAvailable');
            return;
        }

        this.loadPreview();
    },

    loadPreview: function () {
        var me = this;

        if ('0' === this.record.get('preview_count')) {
            this.fireEvent('noPreviewAvailable');
            return;
        }

        lodash.range(this.record.get('preview_count')).forEach(function (previewNumber) {
            var path = this.record.get('path'),
                revision = this.record.get('revision');

            var url = Ext.urlEncode({
                method: 'Tinebase.downloadPreview',
                frontend: 'http',
                _path: path,
                _appId: this.initialApp ? this.initialApp.id : this.app.id,
                _type: 'previews',
                _num: previewNumber,
                _revision: revision
            }, Tine.Tinebase.tineInit.requestUrl + '?');

            me.add({
                html: '<img style="width: 100%;" src="' + url + '" />',
                xtype: 'panel',
                frame: true,
                border: true
            });
        }, this);
    },

    /**
     * Fires if no previews are available
     */
    onNoPreviewAvailable: function () {
        this.html = '<b>' + this.app.i18n._('No preview available.') + '</b>';
    },

    /**
     * @private
     */
    onClose : function(){
        this.fireEvent('cancel');
        this.purgeListeners();
        this.window.close();
    }
});

Tine.Filemanager.DocumentPreview.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    return Tine.WindowFactory.getWindow({
        width: (screen.height * 0.8) / Math.sqrt(2), // DIN A4 and so on
        height: screen.height * 0.8,
        name: Tine.Filemanager.DocumentPreview.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Filemanager.DocumentPreview',
        contentPanelConstructorConfig: config,
        modal: false,
        windowType: 'Browser'
    });
};
