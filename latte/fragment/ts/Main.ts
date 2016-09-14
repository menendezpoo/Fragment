/**
 * Created by josemanuel on 7/14/16.
 */
module latte {

    /**
     *
     */
    export class Main {

        //region Static

        static goEditorView(guid: string){

            Page.byUrlQ(guid).send((p: Page) => {
                View.mainView = new PageEditorView(p);
            });

        }

        static goMainView(){

            let body = new Element<HTMLBodyElement>(document.body);

            body.clear();

            View.mainView = new CmsMainView();
        }

        static goSignInView(){
            let v = new SignInView();
            document.body.appendChild(v.element);
        }

        static logOut(){
            View.mainView = null;
            Session.logOut().send(() => {
                document.location.reload();
            });
        }
        //endregion

        //region Fields
        //endregion

        /**
         *
         */
        constructor() {
            console.log('%cFRAGMENT', 'font-size: 30px; color: #ff4d4d; text-shadow: 3px 3px 3px rgba(0,0,0,0.2); font-family:"Avenir Next","Myriad",sans-serif;');
            console.log('http://github.com/menendezpoo/Fragment');

            _latteUrl('/fragment/latte');

            FragmentAdapterManager.register('text', 'PlainTextFragmentAdapter');
            FragmentAdapterManager.register('html', 'HtmlFragmentAdapter');
            FragmentAdapterManager.register('gallery', 'ImageGalleryFragmentAdapter');


            // View.mainView = new CmsExplorer();

            if(window['loggedFragmentUser']) {
                User.me = <User>DataRecord.fromServerObject(window['loggedFragmentUser']);
                if(window.location.hash.indexOf('#/Editor/') === 0) {
                    Main.goEditorView(window.location.hash.substr(9));
                }else{
                    Main.goMainView();
                }
            }else {
                Main.goSignInView();
            }

        }

        //region Private Methods
        //endregion

        //region Methods
        //endregion

        //region Events
        //endregion

        //region Properties
        //endregion

    }

}