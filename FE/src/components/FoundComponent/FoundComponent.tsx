
import FoundRoute from '../../assets/imgs/template/404.png'
const FoundComponent: React.FC = () => {
    return (
        <>
            <main className="main">
                <div className="container">
                    <div className="box-404 mb-30">
                        <div className="row align-items-center">
                            <div className="col-lg-6 pr-80">
                                <div className="text-5xl text-bold mb-10">Page not available</div>
                                <div className="text-xl text-bold mb-20">
                                    Sorry, but the page you were looking<br />for could not be Component.</div>
                                <div className="text-md mb-30">
                                    You can return to our home page, or drop us a line<br />if you can't find what you're looking for.</div>
                            </div>
                            <div className="col-lg-6 text-end"><img className="d-inline" src={FoundRoute} alt="kidify" /></div>
                        </div>
                    </div>
                    <div className="box-info-contact">
                        <div className="row">
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Chat to sales</strong>
                                        <p className="font-md">Speak to our teamcom</p><a className="font-md" href="#">sales@kidify.com</a>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Call us</strong><a className="font-md" href="#">+01 568 253</a><a className="font-md" href="#">+01 568 253</a></div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Postal mail</strong>
                                        <p className="font-md">456 Park Avenue South, Apt 7B<br />New York, NY 10016</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Social Network</strong>
                                        <p className="font-md">456 Park Avenue South, Apt 7B<br />New York, NY 10016</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <section className="section block-section block-subscriber">
                    <div className="container">
                        <div className="box-subscriber-2">
                            <div className="row align-items-end">
                                <div className="col-lg-1"> </div>
                                <div className="col-lg-5">
                                    <h4 className="heading-4 brand-2 mb-20">Sing up and get up to <span className="brand-3">25% </span>off <br className="d-none d-lg-block" />your first purchase </h4>
                                    <p className="font-md neutral-500 mb-20">Receive offter, product alerts, styling inspiration and more. By signing up, you agree to our Privace Policy</p>
                                </div>
                                <div className="col-lg-5">
                                    <div className="box-form-newsletter mb-20">
                                        <form action="#">
                                            <input className="form-control" type="text" placeholder="Enter your email" />
                                            <button className="btn btn-brand-1">Subscriber</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

        </>
    )
}

export default FoundComponent