async function main() {
    let page = 1;

    let html = '';

    do {
        let url = 'https://www.odcec.ct.it/iscritto/scroll_list/?p=' + page;
        html = (await axios.get(url)).data;

        if (html != '') {
            let search_html = html;
            let occurrency = '';
            page++;
            do {
                //trovo la prima occorrenza di href=" nello html del ciclo
                occurrency = search_html.search(`href="(\\s*[^\\][\\s"]+)`);

                //se non ci sono occorrenze esco dal ciclo, ho finito i risultati per questa pagina
                if (occurrency === -1) {
                    break;
                }
                search_html = search_html.slice(occurrency, search_html.length);

                //controllo se esiste href=" nella stringa
                const index_href = search_html.indexOf("href=\"");
                if (index_href !== -1) {
                    search_html = search_html.slice(index_href + 6, search_html.length);
                }

                let i = search_html.indexOf("\">");
                let url_profile = search_html.slice(0, i);

                if (url_profile.indexOf('scroll_list') === -1) {
                    html = (await axios.get('https://www.odcec.ct.it' + url_profile)).data;

                    document.createElement('div');

                }

            } while (search_html !== '');
        }
    } while(html != '');
}

main();
