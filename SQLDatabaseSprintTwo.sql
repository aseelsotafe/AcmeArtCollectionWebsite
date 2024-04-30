// United Nations Team
// Sprint Two - Agile Web Development
// Murdoch TAFE
// Berlan Binenggar
// Olga Selezneva
// Aseel Al-Ansari
// 30-04-2024



CREATE TABLE IF NOT EXISTS Artists (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    LifeSpan VARCHAR(50), 
    Nationality VARCHAR(50), 
    thumbNailImage BLOB
);
 
INSERT INTO Artists ( Name, LifeSpan, Nationality, thumbNailImage) VALUES 

('August Renoir', '1841–1919', 'French', LOAD_FILE('C:\admin\Download\ThumbNail\renoir.gif')), 

('Michelangelo', '1475–1564', 'Italian', LOAD_FILE('C:\admin\Download\ThumbNail\michelangelo.gif')), 

('Vincent Van Gogh', '1853–1890', 'Dutch', LOAD_FILE('C:\admin\Download\ThumbNail\vangogh.gif')), 

('Claude Monet', '1840–1926', 'French', LOAD_FILE('C:C:\admin\Download\ThumbNail\monet.gif')), 

('Rembrandt', '1606–1669', 'Dutch', LOAD_FILE('C:\admin\Download\ThumbNail\rembrandt.gif')), 

('Pablo Picasso', '1881–1973', 'Spanish', LOAD_FILE('C:\admin\Download\ThumbNail\picasso.gif')), 

('Jan Vermeer', '1632–1675', 'Dutch', LOAD_FILE('C:\admin\Download\ThumbNail\vermeer.gif')), 

('Salvador Dali', '1904–1989', 'Spanish', LOAD_FILE('C:\admin\Download\ThumbNail\Dali.gif')), 

('Paul Cezanne', '1839–1906', 'French', LOAD_FILE('C:\admin\Download\ThumbNail\Cezanne.gif')), 

('Leonardo da Vinci', '1452–1519', 'Italian', LOAD_FILE('C:\admin\Download\ThumbNail\davinci.gif')), 

('Raphael', '1483–1520', 'Italian', LOAD_FILE('C:\admin\Download\ThumbNail\raphael.gif')); 



CREATE TABLE IF NOT EXISTS Styles (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255)
);

INSERT INTO Styles (Name) VALUES
('Impressionism'),
('Mannerism'),
('Still-life'),
('Portrait'),
('Realism'),
('Cubism'),
('Surrealism'),
('Post-Impressionism'),
('Renaissance');



CREATE TABLE IF NOT EXISTS Paintings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255),
    Completed VARCHAR(4),
    Media VARCHAR(50),
    ArtistID INT,
    StyleID INT,
    Thumbnail MEDIUMBLOB,
    Image MEDIUMBLOB,
    FOREIGN KEY (ArtistID) REFERENCES Artists(ID),
    FOREIGN KEY (StyleID) REFERENCES Styles(ID)
);

INSERT INTO Paintings (Title, Completed, Media, ArtistID, StyleID, Thumbnail, Image) VALUES
('Bal du moulin de la Galette', 1876, 'oil', 1, 1, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/baldumoulindelagalette.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/baldumoulindelagalette.png')),
('Doni Tondo (Doni Madonna)', 1506, 'oil', 2, 2, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/donitondo.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/donitondo.png')),
('Vase with Twelve Sunflowers', 1888, 'oil', 3, 3, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/vasewithtwelvesunflowers.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/vasewithtwelvesunflowers.png')),
('Mona Lisa', 1506, 'oil', 4, 4, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/monalisa.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/monalisa.png')),
('The Potato Eaters', 1885, 'oil', 3, 5, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/thepotatoeaters.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/thepotatoeaters.png')),
('Sunrise', 1872, 'oil', 5, 1, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/sunrise.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/sunrise.png')),
('Weaver', 1884, 'oil', 3, 5, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/weaver.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/weaver.png')),
('Nature morte au compotier', 1914, 'oil', 6, 6, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/naturemorteaucompotier.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/naturemorteaucompotier.png')),
('Houses of Parliament', 1904, 'oil', 5, 1, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/housesofparliament.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/housesofparliament.png')),
('Cafe Terrace at Night', 1888, 'oil', 3, 8, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/cafeterraceatnight.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/cafeterraceatnight.png')),
('At the Lapin Agile', 1905, 'oil', 6, 6, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/atthelapinagile.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/atthelapinagile.png')),
('The Persistence of Memory', 1931, 'oil', 7, 7, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/thepersistenceofmemory.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/thepersistenceofmemory.png')),
('The Hallucinogenic Toreador', 1970, 'oil', 7, 7, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/thehallucinogenictoreador.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/thehallucinogenictoreador.png')),
('Jas de Bouffan', 1876, 'oil', 8, 1, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/jazdebouffan.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/jazdebouffan.png')),
('Vitruvian Man', 1490, 'pen-ink', 4, 9, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/vitruvianman.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/vitruvianman.png')),
('The Kingfisher', 1886, 'oil', 3, 8, LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_100_wide/png_100_wide/thekingfisher.png'), LOAD_FILE('C:/Users/GAZ2/Downloads/Paintings/png_500_wide/png_500_wide/thekingfisher.png'));

