import ContactForm from "../../components/ContactComponent/ContactForm";
import Maps from "../../components/ContactComponent/Maps";

const Contact: React.FC = () => {
  return (
    <>
      <main className="main">
        <Maps />
        <ContactForm />
      </main>
    </>
  );
};

export default Contact;
