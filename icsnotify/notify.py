import re, urllib
from BeautifulSoup import BeautifulSoup
import smtplib
import database

soup = BeautifulSoup(urllib.urlopen('http://talk.sonymobile.com/thread/35144'))
db = database.Connection('localhost', 'icsnotify', 'bibhas', 'ec2bipass')
# db = database.Connection('localhost', 'icsnotify', 'root', 'toor')

records = db.query('SELECT * FROM tbl_email WHERE notified=0')

for record in records:
    si = db.get('SELECT number FROM tbl_si WHERE id = %s', record['si_id'])
    soup_elems = soup.find(text=re.compile(si['number']))
    if soup_elems:
        to = record['address'].strip()
        gmail_user = 'iambibhas@gmail.com'
        gmail_pwd = 'varpass=bcltt351'
        smtpserver = smtplib.SMTP("smtp.gmail.com",587)
        smtpserver.ehlo()
        smtpserver.starttls()
        smtpserver.ehlo
        smtpserver.login(gmail_user, gmail_pwd)
        header = 'To:' + to + '\n' + 'From: ' + gmail_user + '\n' + 'Subject: ICS-Notify - ICS is available for your Device! \n'
        # print header
        body_msg = '\n Hey!\nGood news! Your device\'s SI number(%s) just showed up in Sony\'s list for Android 4.0(ICS) availability! Visit http://talk.sonymobile.com/thread/35144 for more details. :)\n\nIf this service helped, give me some feedback on twitter @iAmBibhas. Thanks. \n\n' % si['number']
        msg = header + body_msg
        smtpserver.sendmail(gmail_user, to, msg)
        # print 'done!'
        smtpserver.close()
        db.execute('UPDATE tbl_email SET notified=1 WHERE address=%s', record['address'])