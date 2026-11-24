/**
 * Digital Diary - Pure Static Application Store (LocalStorage)
 */

const STORAGE_KEYS = {
    SETTINGS: 'hiyoweb_settings',
    PAGES: 'hiyoweb_pages',
    AUTH: 'hiyoweb_auth'
};

const DEFAULT_SETTINGS = {
    diary_title: 'A Piece of Us',
    reader_day: 18,
    reader_month: 3,
    reader_year: 2026,
    reader_clue: 'AWAL MARET',
    admin_password: 'admin123'
};

const DEFAULT_PAGES = [
    {
        id: 1,
        page_number: 1,
        song_title: 'Track 01: song',
        audio_file: '',
        layout_type: 'photo_album_4',
        note_title: '',
        note_content: '',
        items: [
            { position: 1, media_type: 'image', file_path: 'uploads/pages/1793775645_6aead81d1fd96.jpg', caption: 'note' },
            { position: 2, media_type: 'image', file_path: 'uploads/pages/HhguIX8QoHYPv5PxCStgRobBsgKHhx118OBQNDxV.jpg', caption: 'note' },
            { position: 3, media_type: 'image', file_path: 'uploads/pages/n1pgw7UGQOAgiAURD47wBjhY6PbgJ4BDm91eypDH.jpg', caption: "DATE\nnote" },
            { position: 4, media_type: 'image', file_path: 'uploads/pages/V2TM0KIgY8oV8009nJh88ZiiHTY4oTVPjwuk6lTJ.jpg', caption: 'note' }
        ]
    },
    {
        id: 2,
        page_number: 2,
        song_title: 'Track 02: song',
        audio_file: '',
        layout_type: 'photo_album_4',
        note_title: '',
        note_content: '',
        items: [
            { position: 1, media_type: 'image', file_path: '', caption: 'note' },
            { position: 2, media_type: 'image', file_path: '', caption: 'note' },
            { position: 3, media_type: 'image', file_path: '', caption: 'note' },
            { position: 4, media_type: 'image', file_path: '', caption: 'note' }
        ]
    },
    {
        id: 3,
        page_number: 3,
        song_title: 'Track 02: you! — LANY',
        audio_file: '',
        layout_type: 'full_media',
        note_title: '',
        note_content: '',
        items: [
            { 
                position: 1, 
                media_type: 'image', 
                file_path: 'uploads/pages/V2TM0KIgY8oV8009nJh88ZiiHTY4oTVPjwuk6lTJ.jpg', 
                caption: "You're the one I needed this whole time\n\nlove u moree, sayangg." 
            }
        ]
    },
    {
        id: 4,
        page_number: 4,
        song_title: 'Track 02: you! — LANY',
        audio_file: '',
        layout_type: 'photo_album_4',
        note_title: '',
        note_content: '',
        items: [
            { position: 1, media_type: 'image', file_path: '', caption: 'GANTENG 1' },
            { position: 2, media_type: 'image', file_path: '', caption: 'GANTENG 2' },
            { 
                position: 3, 
                media_type: 'image', 
                file_path: '', 
                caption: 'ini adalah salah satu hal yang bakal aku ingat seumur hidup aku. bunga, surat, photobooth hahaha. sedih sebenarnya karna bakalan ldr. huwaaaa nangisss lagiiiiiii' 
            },
            { 
                position: 4, 
                media_type: 'image', 
                file_path: '', 
                caption: "You're the one I needed this whole time\n\nlove u moree, sayangg." 
            }
        ]
    },
    {
        id: 5,
        page_number: 5,
        song_title: '',
        audio_file: '',
        layout_type: 'text_only',
        note_title: 'TOMORROW N FOREVER',
        note_content: "You're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nLOVE YOYS",
        items: []
    },
    {
        id: 6,
        page_number: 6,
        song_title: '',
        audio_file: '',
        layout_type: 'text_only',
        note_title: 'MEMORIES & DREAMS',
        note_content: "Every single day spent with you is my favorite day.\nSo, today is my new favorite day.\n\nThank you for being in my life.\nForever and always.",
        items: []
    }
];

const DiaryStore = {
    init() {
        if (!localStorage.getItem(STORAGE_KEYS.SETTINGS)) {
            localStorage.setItem(STORAGE_KEYS.SETTINGS, JSON.stringify(DEFAULT_SETTINGS));
        }
        if (!localStorage.getItem(STORAGE_KEYS.PAGES)) {
            localStorage.setItem(STORAGE_KEYS.PAGES, JSON.stringify(DEFAULT_PAGES));
        }
    },

    getSettings() {
        this.init();
        try {
            return { ...DEFAULT_SETTINGS, ...JSON.parse(localStorage.getItem(STORAGE_KEYS.SETTINGS)) };
        } catch (e) {
            return DEFAULT_SETTINGS;
        }
    },

    saveSettings(newSettings) {
        const current = this.getSettings();
        const merged = { ...current, ...newSettings };
        localStorage.setItem(STORAGE_KEYS.SETTINGS, JSON.stringify(merged));
        return merged;
    },

    getPages() {
        this.init();
        try {
            const pages = JSON.parse(localStorage.getItem(STORAGE_KEYS.PAGES)) || [];
            return pages.sort((a, b) => a.page_number - b.page_number);
        } catch (e) {
            return DEFAULT_PAGES;
        }
    },

    getPage(pageNumber) {
        const pages = this.getPages();
        return pages.find(p => p.page_number === parseInt(pageNumber)) || null;
    },

    savePage(pageData) {
        const pages = this.getPages();
        const num = parseInt(pageData.page_number);
        const existingIndex = pages.findIndex(p => p.page_number === num);

        if (existingIndex > -1) {
            pages[existingIndex] = { ...pages[existingIndex], ...pageData, page_number: num };
        } else {
            const nextId = pages.length > 0 ? Math.max(...pages.map(p => p.id || 0)) + 1 : 1;
            pages.push({ id: nextId, ...pageData, page_number: num });
        }

        pages.sort((a, b) => a.page_number - b.page_number);
        localStorage.setItem(STORAGE_KEYS.PAGES, JSON.stringify(pages));
        return true;
    },

    deletePage(pageNumber) {
        let pages = this.getPages();
        pages = pages.filter(p => p.page_number !== parseInt(pageNumber));
        localStorage.setItem(STORAGE_KEYS.PAGES, JSON.stringify(pages));
        return true;
    },

    resetToDefault() {
        localStorage.setItem(STORAGE_KEYS.SETTINGS, JSON.stringify(DEFAULT_SETTINGS));
        localStorage.setItem(STORAGE_KEYS.PAGES, JSON.stringify(DEFAULT_PAGES));
    },

    // Auth state management
    getAuth() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEYS.AUTH)) || { isReader: false, isAdmin: false };
        } catch (e) {
            return { isReader: false, isAdmin: false };
        }
    },

    setAuth(authData) {
        const current = this.getAuth();
        localStorage.setItem(STORAGE_KEYS.AUTH, JSON.stringify({ ...current, ...authData }));
    },

    logout() {
        localStorage.removeItem(STORAGE_KEYS.AUTH);
        window.location.href = 'index.html';
    },

    verifyReader(day, month, year) {
        const settings = this.getSettings();
        const valid = parseInt(day) === parseInt(settings.reader_day) &&
                      parseInt(month) === parseInt(settings.reader_month) &&
                      parseInt(year) === parseInt(settings.reader_year);
        if (valid) {
            this.setAuth({ isReader: true });
            return { success: true };
        }
        return { success: false, message: 'Tanggal verifikasi salah! Fix ga sayang.' };
    },

    verifyAdmin(password) {
        const settings = this.getSettings();
        if (password === settings.admin_password) {
            this.setAuth({ isAdmin: true, isReader: true });
            return { success: true };
        }
        return { success: false, message: 'Password Admin salah!' };
    }
};

// Utility to convert file to Base64
function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        if (!file) return resolve('');
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

// Auto init on load
DiaryStore.init();
